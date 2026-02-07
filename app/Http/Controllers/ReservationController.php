<?php

namespace App\Http\Controllers;

use App\Models\Manifestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    /**
     * Afficher le formulaire de réservation gratuite
     * @param string $type - Type de manifestation (concert, conference, atelier, exposition)
     * @param int $idmanif - ID de la manifestation
     */
    public function create($type, $idmanif)
    {
        // Recherche avec type ET id pour éviter l'ambiguïté
        $manifestation = Manifestation::findByTypeAndId($type, $idmanif);

        if (!$manifestation) {
            abort(404, 'Manifestation introuvable');
        }

        // Vérifier que la manifestation est gratuite
        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('error', 'Cette manifestation est payante. Utilisez le système de réservation payant.');
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif, $type);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('error', 'Il n\'y a plus de places disponibles pour cette manifestation.');
        }

        $reservationsExistantes = $this->getReservationsUtilisateur($idmanif, Auth::id(), $type);
        $placesDejaReservees = $reservationsExistantes;

        return view('reservations.create', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'placesDejaReservees' => $placesDejaReservees,
            'maxPlacesDisponibles' => min(4 - $placesDejaReservees, $placesRestantes),
            'type' => $type  // Passer le type à la vue
        ]);
    }

    /**
     * Enregistrer une réservation gratuite
     */
    public function store(Request $request, $type, $idmanif)
    {
        $request->validate([
            'nombre_places' => 'required|integer|min:1|max:4'
        ]);

        $manifestation = Manifestation::findByTypeAndId($type, $idmanif);

        if (!$manifestation) {
            abort(404, 'Manifestation introuvable');
        }

        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('error', 'Cette manifestation n\'est pas gratuite.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId, $type);

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cette manifestation.')
                ->withInput();
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif, $type);

        if ($nombrePlaces > $placesRestantes) {
            return redirect()->back()
                ->with('error', "Il n'y a plus assez de places disponibles. Places restantes : $placesRestantes")
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Utiliser le type passé en paramètre (plus fiable)
            $column = Manifestation::getColumnFromType($type);
            
            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif, $type);

                // Préparer les données avec toutes les colonnes idmanif_* à NULL sauf celle concernée
                $data = [
                    'idmanif_concert' => null,
                    'idmanif_conference' => null,
                    'idmanif_atelier' => null,
                    'idmanif_exposition' => null,
                    'iduser' => $userId,
                    'qr_code' => $qrCode,
                    'datereserv' => now(),
                    'idpaiement' => null,
                ];
                
                // Définir le bon idmanif selon le type
                $data[$column] = $idmanif;

                DB::table('billet')->insert($data);
            }

            DB::commit();

            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('success', "Réservation confirmée ! $nombrePlaces place(s) réservée(s). Vous allez recevoir un email de confirmation.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Détecter les erreurs du trigger PostgreSQL
            $message = 'Une erreur est survenue lors de la réservation. Veuillez réessayer.';
            if (str_contains($e->getMessage(), 'capacité maximal')) {
                $message = 'Désolé, la manifestation a atteint sa capacité maximale.';
            } elseif (str_contains($e->getMessage(), '4 réservation')) {
                $message = 'Vous avez déjà atteint le maximum de 4 réservations pour cette manifestation.';
            }
            
            return redirect()->back()
                ->with('error', $message)
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire de réservation payante
     */
    public function createPayant($type, $idmanif)
    {
        $manifestation = Manifestation::findByTypeAndId($type, $idmanif);

        if (!$manifestation) {
            abort(404, 'Manifestation introuvable');
        }

        // Vérifier que la manifestation est payante
        if (is_null($manifestation->prixmanif) || $manifestation->prixmanif <= 0) {
            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('error', 'Cette manifestation est gratuite. Utilisez le système de réservation gratuit.');
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif, $type);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('error', 'Il n\'y a plus de places disponibles pour cette manifestation.');
        }

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, Auth::id(), $type);

        return view('reservations.create-payant', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'placesDejaReservees' => $placesDejaReservees,
            'maxPlacesDisponibles' => min(4 - $placesDejaReservees, $placesRestantes),
            'type' => $type
        ]);
    }

    /**
     * Traiter le paiement et créer la réservation payante
     */
    public function storePayant(Request $request, $type, $idmanif)
    {
        // Validation des données
        $validated = $request->validate([
            'nombre_places' => 'required|integer|min:1|max:4',
            'card_number' => 'required|digits:16',
            'card_name' => 'required|string|max:100',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|digits:3'
        ], [
            'nombre_places.required' => 'Le nombre de places est requis',
            'nombre_places.min' => 'Vous devez réserver au moins 1 place',
            'nombre_places.max' => 'Vous ne pouvez pas réserver plus de 4 places',
            'card_number.required' => 'Le numéro de carte est requis',
            'card_number.digits' => 'Le numéro de carte doit contenir exactement 16 chiffres',
            'card_name.required' => 'Le nom du titulaire est requis',
            'card_expiry.required' => 'La date d\'expiration est requise',
            'card_expiry.size' => 'La date d\'expiration doit être au format MM/AA',
            'card_cvv.required' => 'Le CVV est requis',
            'card_cvv.digits' => 'Le CVV doit contenir exactement 3 chiffres'
        ]);

        // Vérifier le format de la date d'expiration
        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $request->card_expiry)) {
            return redirect()->back()
                ->with('error', 'La date d\'expiration doit être au format MM/AA (ex: 12/28)')
                ->withInput();
        }

        $manifestation = Manifestation::findByTypeAndId($type, $idmanif);

        if (!$manifestation || is_null($manifestation->prixmanif) || $manifestation->prixmanif <= 0) {
            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('error', 'Cette manifestation n\'est pas payante.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId, $type);

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cette manifestation.')
                ->withInput();
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif, $type);

        if ($nombrePlaces > $placesRestantes) {
            return redirect()->back()
                ->with('error', "Il n'y a plus assez de places disponibles. Places restantes : $placesRestantes")
                ->withInput();
        }

        // Calculer le montant total
        $montantTotal = $nombrePlaces * $manifestation->prixmanif;

        // Simuler le paiement
        $paiementReussi = $this->simulerPaiement($request->all(), $montantTotal);

        if (!$paiementReussi) {
            return redirect()->back()
                ->with('error', 'Le paiement a échoué. Veuillez vérifier vos informations bancaires et réessayer.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Utiliser le type passé en paramètre
            $column = Manifestation::getColumnFromType($type);

            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif, $type);

                // Préparer les données avec toutes les colonnes idmanif_* à NULL sauf celle concernée
                $data = [
                    'idmanif_concert' => null,
                    'idmanif_conference' => null,
                    'idmanif_atelier' => null,
                    'idmanif_exposition' => null,
                    'iduser' => $userId,
                    'qr_code' => $qrCode,
                    'datereserv' => now(),
                    'idpaiement' => 3, // 3 = Carte bancaire
                ];
                
                // Définir le bon idmanif selon le type
                $data[$column] = $idmanif;

                DB::table('billet')->insert($data);
            }

            DB::commit();

            return redirect()->route('manifestations.show', ['type' => $type, 'id' => $idmanif])
                ->with('success', "Paiement de " . number_format($montantTotal, 2, ',', ' ') . " € réussi ! $nombrePlaces place(s) réservée(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur réservation payante: ' . $e->getMessage());
            
            // Détecter les erreurs du trigger PostgreSQL
            $message = 'Une erreur est survenue lors de la réservation.';
            if (str_contains($e->getMessage(), 'capacité maximal')) {
                $message = 'Désolé, la manifestation a atteint sa capacité maximale.';
            } elseif (str_contains($e->getMessage(), '4 réservation')) {
                $message = 'Vous avez déjà atteint le maximum de 4 réservations pour cette manifestation.';
            }
            
            return redirect()->back()
                ->with('error', $message)
                ->withInput();
        }
    }

    /**
     * Récupérer le nombre de places déjà réservées par un utilisateur
     * @param int $idmanif - ID de la manifestation
     * @param int $userId - ID de l'utilisateur
     * @param string $type - Type de manifestation (concert, conference, etc.)
     */
    private function getReservationsUtilisateur($idmanif, $userId, $type)
    {
        // Utiliser uniquement la colonne correspondant au type
        $column = Manifestation::getColumnFromType($type);
        
        return DB::table('billet')
            ->where('iduser', $userId)
            ->where($column, $idmanif)
            ->count();
    }

    /**
     * Générer un numéro de billet unique
     */
    private function genererNumeroBillet()
    {
        do {
            $numero = 'TICKET-' . strtoupper(Str::random(10));
            $existe = DB::table('billet')->where('qr_code', $numero)->exists();
        } while ($existe);

        return $numero;
    }

    /**
     * Générer un code QR pour le billet
     * @param string $numeroBillet - Numéro du billet
     * @param int $idmanif - ID de la manifestation
     * @param string $type - Type de manifestation
     * @param string|null $date - Date de la séance (pour les ateliers)
     */
    private function genererQRCode($numeroBillet, $idmanif, $type = 'concert', $date = null)
    {
        // Format du QR code : BILLET|NUMERO|TYPE|MANIFESTATION|DATE|TIMESTAMP
        $parts = [
            'BILLET',
            $numeroBillet,
            strtoupper($type),
            $idmanif,
        ];
        
        // Ajouter la date si c'est un atelier
        if ($date) {
            $parts[] = $date;
        }
        
        $parts[] = time();

        return base64_encode(implode('|', $parts));
    }

    /**
     * Simuler un paiement par carte bancaire
     */
    private function simulerPaiement($cardData, $montant)
    {
        $cardNumber = $cardData['card_number'];
        
        // Validation du numéro de carte (algorithme de Luhn simplifié)
        if (!$this->validateCardNumber($cardNumber)) {
            return false;
        }
        
        // Simuler un échec pour les cartes se terminant par 0000
        if (substr($cardNumber, -4) === '0000') {
            return false;
        }
        
        // Simuler un délai de traitement
        usleep(500000); // 0.5 secondes
        
        // 95% de succès
        return rand(1, 100) <= 95;
    }

    /**
     * Valider un numéro de carte bancaire (algorithme de Luhn simplifié)
     */
    private function validateCardNumber($number)
    {
        if (!preg_match('/^[0-9]{16}$/', $number)) {
            return false;
        }

        $sum = 0;
        $alt = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];
            
            if ($alt) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
            $alt = !$alt;
        }

        return ($sum % 10 === 0);
    }

    // ========================================
    // MÉTHODES SPÉCIFIQUES AUX ATELIERS
    // ========================================

    /**
     * Afficher le formulaire de réservation gratuite pour un atelier
     */
    public function createAtelier($idmanif, $date)
    {
        $manifestation = Manifestation::findAtelierByIdAndDate($idmanif, $date);

        if (!$manifestation) {
            abort(404, 'Séance d\'atelier introuvable');
        }

        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('error', 'Cette séance est payante. Utilisez le système de réservation payant.');
        }

        $placesRestantes = Manifestation::getPlacesRestantesAtelier($idmanif, $date);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('error', 'Il n\'y a plus de places disponibles pour cette séance.');
        }

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, Auth::id(), 'atelier');

        return view('reservations.create', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'placesDejaReservees' => $placesDejaReservees,
            'maxPlacesDisponibles' => min(4 - $placesDejaReservees, $placesRestantes),
            'type' => 'atelier',
            'date' => $date
        ]);
    }

    /**
     * Enregistrer une réservation gratuite pour un atelier
     */
    public function storeAtelier(Request $request, $idmanif, $date)
    {
        $request->validate([
            'nombre_places' => 'required|integer|min:1|max:4'
        ]);

        $manifestation = Manifestation::findAtelierByIdAndDate($idmanif, $date);

        if (!$manifestation) {
            abort(404, 'Séance d\'atelier introuvable');
        }

        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('error', 'Cette séance n\'est pas gratuite.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId, 'atelier');

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cet atelier.')
                ->withInput();
        }

        $placesRestantes = Manifestation::getPlacesRestantesAtelier($idmanif, $date);

        if ($nombrePlaces > $placesRestantes) {
            return redirect()->back()
                ->with('error', "Il n'y a plus assez de places disponibles. Places restantes : $placesRestantes")
                ->withInput();
        }

        try {
            DB::beginTransaction();

            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif, 'atelier', $date);

                $data = [
                    'idmanif_concert' => null,
                    'idmanif_conference' => null,
                    'idmanif_atelier' => $idmanif,
                    'idmanif_exposition' => null,
                    'iduser' => $userId,
                    'qr_code' => $qrCode,
                    'datereserv' => now(),
                    'idpaiement' => null,
                ];

                DB::table('billet')->insert($data);
            }

            DB::commit();

            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('success', "Réservation confirmée ! $nombrePlaces place(s) réservée(s) pour la séance du $date.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            $message = 'Une erreur est survenue lors de la réservation. Veuillez réessayer.';
            if (str_contains($e->getMessage(), 'capacité maximal')) {
                $message = 'Désolé, cette séance a atteint sa capacité maximale.';
            } elseif (str_contains($e->getMessage(), '4 réservation')) {
                $message = 'Vous avez déjà atteint le maximum de 4 réservations pour cet atelier.';
            }
            
            return redirect()->back()
                ->with('error', $message)
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire de réservation payante pour un atelier
     */
    public function createPayantAtelier($idmanif, $date)
    {
        $manifestation = Manifestation::findAtelierByIdAndDate($idmanif, $date);

        if (!$manifestation) {
            abort(404, 'Séance d\'atelier introuvable');
        }

        if (is_null($manifestation->prixmanif) || $manifestation->prixmanif <= 0) {
            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('error', 'Cette séance est gratuite. Utilisez le système de réservation gratuit.');
        }

        $placesRestantes = Manifestation::getPlacesRestantesAtelier($idmanif, $date);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('error', 'Il n\'y a plus de places disponibles pour cette séance.');
        }

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, Auth::id(), 'atelier');

        return view('reservations.create-payant', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'placesDejaReservees' => $placesDejaReservees,
            'maxPlacesDisponibles' => min(4 - $placesDejaReservees, $placesRestantes),
            'type' => 'atelier',
            'date' => $date
        ]);
    }

    /**
     * Traiter le paiement et créer la réservation payante pour un atelier
     */
    public function storePayantAtelier(Request $request, $idmanif, $date)
    {
        $validated = $request->validate([
            'nombre_places' => 'required|integer|min:1|max:4',
            'card_number' => 'required|digits:16',
            'card_name' => 'required|string|max:100',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|digits:3'
        ]);

        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $request->card_expiry)) {
            return redirect()->back()
                ->with('error', 'La date d\'expiration doit être au format MM/AA (ex: 12/28)')
                ->withInput();
        }

        $manifestation = Manifestation::findAtelierByIdAndDate($idmanif, $date);

        if (!$manifestation || is_null($manifestation->prixmanif) || $manifestation->prixmanif <= 0) {
            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('error', 'Cette séance n\'est pas payante.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId, 'atelier');

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cet atelier.')
                ->withInput();
        }

        $placesRestantes = Manifestation::getPlacesRestantesAtelier($idmanif, $date);

        if ($nombrePlaces > $placesRestantes) {
            return redirect()->back()
                ->with('error', "Il n'y a plus assez de places disponibles. Places restantes : $placesRestantes")
                ->withInput();
        }

        $montantTotal = $nombrePlaces * $manifestation->prixmanif;
        $paiementReussi = $this->simulerPaiement($request->all(), $montantTotal);

        if (!$paiementReussi) {
            return redirect()->back()
                ->with('error', 'Le paiement a échoué. Veuillez vérifier vos informations bancaires et réessayer.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif, 'atelier', $date);

                $data = [
                    'idmanif_concert' => null,
                    'idmanif_conference' => null,
                    'idmanif_atelier' => $idmanif,
                    'idmanif_exposition' => null,
                    'iduser' => $userId,
                    'qr_code' => $qrCode,
                    'datereserv' => now(),
                    'idpaiement' => 3, // 3 = Carte bancaire
                ];

                DB::table('billet')->insert($data);
            }

            DB::commit();

            return redirect()->route('manifestations.show.atelier', ['id' => $idmanif, 'date' => $date])
                ->with('success', "Paiement de " . number_format($montantTotal, 2, ',', ' ') . " € réussi ! $nombrePlaces place(s) réservée(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur réservation payante atelier: ' . $e->getMessage());
            
            $message = 'Une erreur est survenue lors de la réservation.';
            if (str_contains($e->getMessage(), 'capacité maximal')) {
                $message = 'Désolé, cette séance a atteint sa capacité maximale.';
            } elseif (str_contains($e->getMessage(), '4 réservation')) {
                $message = 'Vous avez déjà atteint le maximum de 4 réservations pour cet atelier.';
            }
            
            return redirect()->back()
                ->with('error', $message)
                ->withInput();
        }
    }

    /**
     * Afficher les réservations de l'utilisateur connecté
     */
    public function mesReservations()
    {
        $userId = Auth::id();

        // Récupérer tous les billets de l'utilisateur avec les informations des manifestations
        $billets = DB::table('billet')
            ->where('iduser', $userId)
            ->leftJoin('all_manifestation as manif', function($join) {
                $join->on('billet.idmanif_concert', '=', 'manif.idmanif')
                    ->orOn('billet.idmanif_conference', '=', 'manif.idmanif')
                    ->orOn('billet.idmanif_atelier', '=', 'manif.idmanif')
                    ->orOn('billet.idmanif_exposition', '=', 'manif.idmanif');
            })
            ->select(
                'billet.*',
                'manif.nommanif',
                'manif.dateheure',
                'manif.type_manifestation',
                'manif.libellelieu',
                'manif.prixmanif'
            )
            ->orderBy('manif.dateheure', 'desc')
            ->get();

        // Grouper par manifestation
        $reservations = $billets->groupBy('nommanif')->map(function ($group) {
            return [
                'manifestation' => $group->first(),
                'nombre_billets' => $group->count(),
                'billets' => $group
            ];
        });

        return view('reservations.index', [
            'reservations' => $reservations
        ]);
    }

}