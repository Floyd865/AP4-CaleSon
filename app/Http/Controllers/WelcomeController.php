<?php

namespace App\Http\Controllers;

use App\Models\Manifestation;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Afficher la page d'accueil avec les manifestations
     */
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

        return view('welcome', [
            'manifestations' => $manifestations,
            'filterOptions' => $filterOptions,
            'filters' => $filters
        ]);
    }
}