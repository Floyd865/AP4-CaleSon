<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Manifestation extends Model
{
    protected $table = 'all_manifestation';
    protected $primaryKey = 'idmanif';
    public $timestamps = false;

    // Utiliser la vue pour récupérer toutes les manifestations
    public static function getAllManifestations($filters = [])
    {
        $query = DB::table('all_manifestation')
            ->select([
                'idmanif',
                'nommanif',
                'resumemanif',
                'prixmanif',
                'idcom',
                'idtheme',
                'idlieu',
                'libellelieu',
                'effectif_complet',
                'dateheure',
                'duree',
                'type_manifestation',
                'libellecom',
                'libelletheme',
                'annetheme',
                'dateheure_fin'
            ])
            ->orderBy('dateheure', 'asc');

        // Filtrer par type
        if (!empty($filters['type'])) {
            $query->where('type_manifestation', $filters['type']);
        }

        // Filtrer par date
        if (!empty($filters['date'])) {
            $query->whereDate('dateheure', $filters['date']);
        }

        // Filtrer par lieu
        if (!empty($filters['lieu'])) {
            $query->where('idlieu', $filters['lieu']);
        }

        // Filtrer par public (communauté)
        if (!empty($filters['communaute'])) {
            $query->where('idcom', $filters['communaute']);
        }

        // Filtrer payantes/gratuites
        if (isset($filters['payant'])) {
            if ($filters['payant'] === 'oui') {
                $query->whereNotNull('prixmanif');
            } elseif ($filters['payant'] === 'non') {
                $query->whereNull('prixmanif');
            }
        }

        return $query->get();
    }

    // Récupérer les filtres disponibles
    public static function getFilterOptions()
    {
        return [
            'types' => DB::table('all_manifestation')
                ->select('type_manifestation')
                ->distinct()
                ->orderBy('type_manifestation')
                ->pluck('type_manifestation'),
            
            'lieux' => DB::table('lieu')
                ->select('idlieu', 'libellelieu')
                ->orderBy('libellelieu')
                ->get(),
            
            'communautes' => DB::table('communaute')
                ->select('idcom', 'libellecom')
                ->orderBy('libellecom')
                ->get()
        ];
    }

    // Calculer les places restantes
    public static function getPlacesRestantes($idmanif)
    {
        $manif = DB::table('all_manifestation')
            ->where('idmanif', $idmanif)
            ->first();

        if (!$manif) {
            return 0;
        }

        $reservations = DB::table('billet')
            ->where('idmanif_concert', $idmanif)
            ->orWhere('idmanif_conference', $idmanif)
            ->orWhere('idmanif_atelier', $idmanif)
            ->orWhere('idmanif_exposition', $idmanif)
            ->count();

        return $manif->effectif_complet - $reservations;
    }
}