<?php

namespace App\Http\Controllers;

use App\Models\Manifestation;
use Illuminate\Http\Request;
use App\Models\Avis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManifestationController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les filtres de la requête
        $filters = [
            'type' => $request->input('type'),
            'date' => $request->input('date'),
            'lieu' => $request->input('lieu'),
            'communaute' => $request->input('communaute'),
            'payant' => $request->input('payant')
        ];

        // Récupérer les manifestations avec filtres
        $manifestations = Manifestation::filter($filters);

        // Calculer les places restantes pour chaque manifestation
        foreach ($manifestations as $manif) {
            $typeSlug = Manifestation::getTypeSlugFromLabel($manif->type_manifestation);
            $manif->type_slug = $typeSlug;
            
            // Pour les ateliers, ajouter le date_slug et calculer places avec la date
            if ($typeSlug === 'atelier') {
                $manif->date_slug = Manifestation::getDateSlug($manif->dateheure);
                $manif->places_restantes = Manifestation::getPlacesRestantesAtelier($manif->idmanif, $manif->date_slug);
            } else {
                $manif->date_slug = null;
                $manif->places_restantes = Manifestation::getPlacesRestantes($manif->idmanif, $typeSlug);
            }
        }

        // Récupérer les options de filtrage
        $filterOptions = Manifestation::getFilterOptions();

        return view('manifestations.index', [
            'manifestations' => $manifestations,
            'filterOptions' => $filterOptions,
            'filters' => $filters
        ]);
    }

    /**
     * Afficher une manifestation (Concert/Conférence/Exposition)
     */
    public function show($type, $id)
    {
        $manifestation = Manifestation::findByTypeAndId($type, $id);

        if (!$manifestation) {
            abort(404);
        }

        $placesRestantes = Manifestation::getPlacesRestantes($id, $type);

        $affiche = DB::table('affiche')
            ->where('idtheme', $manifestation->idtheme)
            ->first();

        // Récupérer les avis - MODIFIÉ : ajout du paramètre $type
        $avis = Avis::getAvisForManifestation($id, $type);
        $averageNote = Avis::getAverageNote($id, $type);
        $totalAvis = Avis::countAvis($id, $type);
        $noteDistribution = Avis::getNoteDistribution($id, $type);
        
        // Vérifier si l'utilisateur connecté a déjà donné un avis - MODIFIÉ : ajout du paramètre $type
        $userHasReviewed = Auth::check() ? Avis::userHasReviewed($id, Auth::id(), $type) : false;
        
        // Vérifier si l'utilisateur a réservé
        $userHasReservation = Auth::check() ? Avis::userHasReservation($id, Auth::id(), $type) : false;

        return view('manifestations.show', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'affiche' => $affiche,
            'type' => $type,
            'date' => null,
            'avis' => $avis,
            'averageNote' => $averageNote,
            'totalAvis' => $totalAvis,
            'noteDistribution' => $noteDistribution,
            'userHasReviewed' => $userHasReviewed,
            'userHasReservation' => $userHasReservation
        ]);
    }
    
    /**
     * Afficher un atelier (avec date pour identifier la séance)
     */
    public function showAtelier($id, $date)
    {
        $manifestation = Manifestation::findAtelierByIdAndDate($id, $date);

        if (!$manifestation) {
            abort(404, 'Séance d\'atelier introuvable');
        }

        $placesRestantes = Manifestation::getPlacesRestantesAtelier($id, $date);

        $affiche = DB::table('affiche')
            ->where('idtheme', $manifestation->idtheme)
            ->first();

        // Récupérer les avis - MODIFIÉ : ajout du paramètre 'atelier'
        $avis = Avis::getAvisForManifestation($id, 'atelier');
        $averageNote = Avis::getAverageNote($id, 'atelier');
        $totalAvis = Avis::countAvis($id, 'atelier');
        $noteDistribution = Avis::getNoteDistribution($id, 'atelier');
        
        // Vérifier si l'utilisateur connecté a déjà donné un avis - MODIFIÉ : ajout du paramètre 'atelier'
        $userHasReviewed = Auth::check() ? Avis::userHasReviewed($id, Auth::id(), 'atelier') : false;
        
        // Vérifier si l'utilisateur a réservé
        $userHasReservation = Auth::check() ? Avis::userHasReservation($id, Auth::id(), 'atelier') : false;

        return view('manifestations.show', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'affiche' => $affiche,
            'type' => 'atelier',
            'date' => $date,
            'avis' => $avis,
            'averageNote' => $averageNote,
            'totalAvis' => $totalAvis,
            'noteDistribution' => $noteDistribution,
            'userHasReviewed' => $userHasReviewed,
            'userHasReservation' => $userHasReservation
        ]);
    }
}