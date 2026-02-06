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
     */
    public function create($idmanif)
    {
        $manifestation = Manifestation::where('idmanif', $idmanif)->first();

        if (!$manifestation) {
            abort(404, 'Manifestation introuvable');
        }

        // Vérifier que la manifestation est gratuite
        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Cette manifestation est payante. Utilisez le système de réservation payant.');
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Il n\'y a plus de places disponibles pour cette manifestation.');
        }

        $reservationsExistantes = $this->getReservationsUtilisateur($idmanif, Auth::id());
        $placesDejaReservees = $reservationsExistantes;

        return view('reservations.create', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'placesDejaReservees' => $placesDejaReservees,
            'maxPlacesDisponibles' => min(4 - $placesDejaReservees, $placesRestantes)
        ]);
    }

    /**
     * Enregistrer une réservation gratuite
     */
    public function store(Request $request, $idmanif)
    {
        $request->validate([
            'nombre_places' => 'required|integer|min:1|max:4'
        ]);

        $manifestation = Manifestation::where('idmanif', $idmanif)->first();

        if (!$manifestation) {
            abort(404, 'Manifestation introuvable');
        }

        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Cette manifestation n\'est pas gratuite.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId);

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cette manifestation.')
                ->withInput();
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif);

        if ($nombrePlaces > $placesRestantes) {
            return redirect()->back()
                ->with('error', "Il n'y a plus assez de places disponibles. Places restantes : $placesRestantes")
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $typeManif = $this->getTypeManifestationColumn($manifestation->type_manifestation);
            
            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif);

                // Préparer les données avec toutes les colonnes idmanif_* à NULL sauf celle concernée
                $data = [
                    'idmanif_concert' => DB::raw('NULL'),
                    'idmanif_conference' => DB::raw('NULL'),
                    'idmanif_atelier' => DB::raw('NULL'),
                    'idmanif_exposition' => DB::raw('NULL'),
                    'iduser' => $userId,
                    'qr_code' => $qrCode,
                    'datereserv' => now(),
                    'idpaiement' => DB::raw('NULL'),
                ];
                
                // Définir le bon idmanif selon le type
                $data[$typeManif] = $idmanif;

                DB::table('billet')->insert($data);
            }

            DB::commit();

            return redirect()->route('manifestations.show', $idmanif)
                ->with('success', "Réservation confirmée ! $nombrePlaces place(s) réservée(s). Vous allez recevoir un email de confirmation.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire de réservation payante
     */
    public function createPayant($idmanif)
    {
        $manifestation = Manifestation::where('idmanif', $idmanif)->first();

        if (!$manifestation) {
            abort(404, 'Manifestation introuvable');
        }

        // Vérifier que la manifestation est payante
        if (is_null($manifestation->prixmanif) || $manifestation->prixmanif <= 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Cette manifestation est gratuite. Utilisez le système de réservation gratuit.');
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Il n\'y a plus de places disponibles pour cette manifestation.');
        }

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, Auth::id());

        return view('reservations.create-payant', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'placesDejaReservees' => $placesDejaReservees,
            'maxPlacesDisponibles' => min(4 - $placesDejaReservees, $placesRestantes)
        ]);
    }

    /**
     * Traiter le paiement et créer la réservation payante
     */
    public function storePayant(Request $request, $idmanif)
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

        $manifestation = Manifestation::where('idmanif', $idmanif)->first();

        if (!$manifestation || is_null($manifestation->prixmanif) || $manifestation->prixmanif <= 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Cette manifestation n\'est pas payante.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId);

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cette manifestation.')
                ->withInput();
        }

        $placesRestantes = Manifestation::getPlacesRestantes($idmanif);

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

            // Créer les billets
            $typeManif = $this->getTypeManifestationColumn($manifestation->type_manifestation);
            $billets = [];

            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif);

                // Préparer les données avec toutes les colonnes idmanif_* à NULL sauf celle concernée
                $data = [
                    'idmanif_concert' => DB::raw(''),
                    'idmanif_conference' => DB::raw(''),
                    'idmanif_atelier' => DB::raw(''),
                    'idmanif_exposition' => DB::raw(''),
                    'iduser' => $userId,
                    'qr_code' => $qrCode,
                    'datereserv' => now(),
                    'idpaiement' => DB::raw('NULL'),
                ];
                
                // Définir le bon idmanif selon le type
                $data[$typeManif] = $idmanif;

                $idBillet = DB::table('billet')->insertGetId($data);

                $billets[] = [
                    'idreserve' => $idBillet,
                    'numero' => $numeroBillet,
                    'qr_code' => $qrCode
                ];
            }

            DB::commit();

            return redirect()->route('manifestations.show', $idmanif)
                ->with('success', "Paiement de " . number_format($montantTotal, 2, ',', ' ') . " € réussi ! $nombrePlaces place(s) réservée(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Erreur réservation payante: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la réservation : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Récupérer le nombre de places déjà réservées par un utilisateur
     */
    private function getReservationsUtilisateur($idmanif, $userId)
    {
        return DB::table('billet')
            ->where('iduser', $userId)
            ->where(function($query) use ($idmanif) {
                $query->where('idmanif_concert', $idmanif)
                    ->orWhere('idmanif_conference', $idmanif)
                    ->orWhere('idmanif_atelier', $idmanif)
                    ->orWhere('idmanif_exposition', $idmanif);
            })
            ->count();
    }

    /**
     * Déterminer la colonne à utiliser selon le type de manifestation
     */
    private function getTypeManifestationColumn($type)
    {
        $mapping = [
            'Concert' => 'idmanif_concert',
            'Conférence' => 'idmanif_conference',
            'Atelier' => 'idmanif_atelier',
            'Exposition' => 'idmanif_exposition'
        ];

        return $mapping[$type] ?? 'idmanif_concert';
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
     */
    private function genererQRCode($numeroBillet, $idmanif)
    {
        // Format du QR code : BILLET|NUMERO|MANIFESTATION|TIMESTAMP
        $data = implode('|', [
            'BILLET',
            $numeroBillet,
            $idmanif,
            time()
        ]);

        return base64_encode($data);
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