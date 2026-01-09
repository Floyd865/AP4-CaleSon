<?php

namespace App\Http\Controllers;

use App\Models\Manifestation;
use Illuminate\Http\Request;

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
        $manifestations = Manifestation::getAllManifestations($filters);

        // Calculer les places restantes pour chaque manifestation
        foreach ($manifestations as $manif) {
            $manif->places_restantes = Manifestation::getPlacesRestantes($manif->idmanif);
        }

        // Récupérer les options de filtrage
        $filterOptions = Manifestation::getFilterOptions();

        return view('manifestations.index', [
            'manifestations' => $manifestations,
            'filterOptions' => $filterOptions,
            'filters' => $filters
        ]);
    }

    public function show($id)
    {
        $manifestation = \DB::table('all_manifestation')
            ->where('idmanif', $id)
            ->first();

        if (!$manifestation) {
            abort(404);
        }

        $placesRestantes = Manifestation::getPlacesRestantes($id);

        // Récupérer l'affiche si elle existe
        $affiche = \DB::table('affiche')
            ->where('idtheme', $manifestation->idtheme)
            ->first();

        return view('manifestations.show', [
            'manifestation' => $manifestation,
            'placesRestantes' => $placesRestantes,
            'affiche' => $affiche
        ]);
    }
}