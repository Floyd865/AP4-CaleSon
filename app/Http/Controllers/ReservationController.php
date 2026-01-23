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
     * Afficher le formulaire de réservation
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

        // Calculer les places restantes
        $placesRestantes = Manifestation::getPlacesRestantes($idmanif);

        if ($placesRestantes <= 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Il n\'y a plus de places disponibles pour cette manifestation.');
        }

        // Vérifier si l'utilisateur a déjà des réservations pour cette manifestation
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
     * Enregistrer une réservation
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

        // Vérifier que la manifestation est gratuite
        if (!is_null($manifestation->prixmanif) && $manifestation->prixmanif > 0) {
            return redirect()->route('manifestations.show', $idmanif)
                ->with('error', 'Cette manifestation n\'est pas gratuite.');
        }

        $nombrePlaces = $request->input('nombre_places');
        $userId = Auth::id();

        // Vérifier les réservations existantes
        $placesDejaReservees = $this->getReservationsUtilisateur($idmanif, $userId);

        if ($placesDejaReservees + $nombrePlaces > 4) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver plus de 4 places au total pour cette manifestation.')
                ->withInput();
        }

        // Vérifier la disponibilité selon la jauge
        $placesRestantes = Manifestation::getPlacesRestantes($idmanif);

        if ($nombrePlaces > $placesRestantes) {
            return redirect()->back()
                ->with('error', "Il n'y a plus assez de places disponibles. Places restantes : $placesRestantes")
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Déterminer le type de manifestation et créer les billets
            $typeManif = $this->getTypeManifestationColumn($manifestation->type_manifestation);
            
            for ($i = 0; $i < $nombrePlaces; $i++) {
                $numeroBillet = $this->genererNumeroBillet();
                $qrCode = $this->genererQRCode($numeroBillet, $idmanif);

                DB::table('billet')->insert([
                    $typeManif => $idmanif,
                    'idinscription' => $userId,
                    'numticket' => $numeroBillet,
                    'codeqr' => $qrCode,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            // TODO: Envoyer l'email de confirmation avec les QR codes
            // $this->envoyerEmailConfirmation($userId, $manifestation, $nombrePlaces);

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
     * Récupérer le nombre de places déjà réservées par un utilisateur
     */
    private function getReservationsUtilisateur($idmanif, $userId)
    {
        return DB::table('billet')
            ->where('idinscription', $userId)
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
            $existe = DB::table('billet')->where('numticket', $numero)->exists();
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

        // Encoder en base64 pour simplifier le stockage
        return base64_encode($data);
    }

    /**
     * Afficher les réservations de l'utilisateur connecté
     */
    public function mesReservations()
    {
        $userId = Auth::id();

        $reservations = DB::table('billet')
            ->join('all_manifestation', function($join) {
                $join->on('billet.idmanif_concert', '=', 'all_manifestation.idmanif')
                    ->orOn('billet.idmanif_conference', '=', 'all_manifestation.idmanif')
                    ->orOn('billet.idmanif_atelier', '=', 'all_manifestation.idmanif')
                    ->orOn('billet.idmanif_exposition', '=', 'all_manifestation.idmanif');
            })
            ->where('billet.idinscription', $userId)
            ->select('billet.*', 'all_manifestation.*')
            ->orderBy('all_manifestation.dateheure', 'asc')
            ->get();

        return view('reservations.index', [
            'reservations' => $reservations
        ]);
    }
}