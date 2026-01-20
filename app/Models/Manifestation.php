<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Manifestation extends Model
{
    // Utilisez la vue au lieu de la table
    protected $table = 'all_manifestation';
    
    public $timestamps = false;
    
    protected $primaryKey = 'idmanif';

    // Filtrer les manifestations
    public static function filter($filters = [])
    {
        $query = self::query();

        // Filtrer par type
        if (!empty($filters['type'])) {
            $type = $filters['type'];
            $query->where(function($q) use ($type) {
                if ($type == 'Concert') {
                    $q->where('type_manifestation', 'Concert');
                } elseif ($type == 'Conférence') {
                    $q->where('type_manifestation', 'Conférence');
                } elseif ($type == 'Atelier') {
                    $q->where('type_manifestation', 'Atelier');
                } elseif ($type == 'Exposition') {
                    $q->where('type_manifestation', 'Exposition');
                }
            });
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
                $query->where('prixmanif', '>', 0);
            } elseif ($filters['payant'] === 'non') {
                $query->where(function($q) {
                    $q->whereNull('prixmanif')
                      ->orWhere('prixmanif', '=', 0);
                });
            }
        }

        return $query->orderBy('dateheure', 'asc')->get();
    }

    // Récupérer les filtres disponibles
    public static function getFilterOptions()
    {
        // Types disponibles basés sur les tables spécifiques
        $types = collect(['Concert', 'Conférence', 'Atelier', 'Exposition']);

        return [
            'types' => $types,
            
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
        // Récupérer l'effectif maximum depuis la vue
        $manifestation = self::where('idmanif', $idmanif)->first();
        
        if (!$manifestation || !$manifestation->effectif_complet) {
            return 0;
        }

        // Compter les réservations
        $reservations = DB::table('billet')
            ->where(function($query) use ($idmanif) {
                $query->where('idmanif_concert', $idmanif)
                    ->orWhere('idmanif_conference', $idmanif)
                    ->orWhere('idmanif_atelier', $idmanif)
                    ->orWhere('idmanif_exposition', $idmanif);
            })
            ->count();

        return max(0, $manifestation->effectif_complet - $reservations);
    }
    
    // Récupérer toutes les manifestations à venir
    public static function getUpcoming($limit = null)
    {
        $query = self::where('dateheure', '>=', now())
                    ->orderBy('dateheure', 'asc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
    
    // Récupérer une manifestation avec tous ses détails
    public static function getWithDetails($idmanif)
    {
        return self::where('idmanif', $idmanif)->first();
    }
}