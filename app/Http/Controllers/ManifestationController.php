<?php

namespace App\Http\Controllers;

use App\Models\Manifestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('manifestations.show', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'affiche' => $affiche,
            'type' => $type,
            'date' => null  // Pas de date pour ces types
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

        return view('manifestations.show', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'affiche' => $affiche,
            'type' => 'atelier',
            'date' => $date  // Passer la date pour les URLs de réservation
        ]);
    }
}