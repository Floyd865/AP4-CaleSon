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
        // CORRECTION : Retourner un tableau simple au lieu d'une Collection
        $types = ['Concert', 'Conférence', 'Atelier', 'Exposition'];

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

    // Calculer les places restantes - AVEC type pour éviter l'ambiguïté
    public static function getPlacesRestantes($idmanif, $type = null)
    {
        // Récupérer l'effectif maximum depuis la vue
        $query = self::where('idmanif', $idmanif);
        
        // Si le type est fourni, on filtre aussi par type
        if ($type) {
            $typeLabel = self::getTypeLabelFromSlug($type);
            $query->where('type_manifestation', $typeLabel);
        }
        
        $manifestation = $query->first();
        
        if (!$manifestation || !$manifestation->effectif_complet) {
            return 0;
        }

        // Compter les réservations UNIQUEMENT pour le bon type
        $column = self::getColumnFromType($type ?? self::getTypeSlugFromLabel($manifestation->type_manifestation));
        
        $reservations = DB::table('billet')
            ->where($column, $idmanif)
            ->count();

        return max(0, $manifestation->effectif_complet - $reservations);
    }
    
    // Récupérer une manifestation par ID ET type (sans ambiguïté)
    // Pour Concert/Conférence/Exposition uniquement
    public static function findByTypeAndId($type, $idmanif)
    {
        $typeLabel = self::getTypeLabelFromSlug($type);
        return self::where('idmanif', $idmanif)
                   ->where('type_manifestation', $typeLabel)
                   ->first();
    }
    
    // Récupérer un atelier par ID ET date (pour les séances multiples)
    public static function findAtelierByIdAndDate($idmanif, $date)
    {
        return self::where('idmanif', $idmanif)
                   ->where('type_manifestation', 'Atelier')
                   ->whereDate('dateheure', $date)
                   ->first();
    }
    
    // Calculer les places restantes pour un atelier (avec date)
    public static function getPlacesRestantesAtelier($idmanif, $date)
    {
        $manifestation = self::findAtelierByIdAndDate($idmanif, $date);
        
        if (!$manifestation || !$manifestation->effectif_complet) {
            return 0;
        }

        // Pour les ateliers, on compte les billets pour cette manifestation
        // Note: les billets d'atelier sont liés à idmanif, pas à la date
        // Car un billet = accès à l'atelier (toutes séances ou séance spécifique selon règle métier)
        $reservations = DB::table('billet')
            ->where('idmanif_atelier', $idmanif)
            ->count();

        return max(0, $manifestation->effectif_complet - $reservations);
    }
    
    // Obtenir le slug de date pour les URLs (format YYYY-MM-DD)
    public static function getDateSlug($dateheure)
    {
        if (!$dateheure) return null;
        return date('Y-m-d', strtotime($dateheure));
    }
    
    // Convertir le slug URL en label de type
    public static function getTypeLabelFromSlug($slug)
    {
        $mapping = [
            'concert' => 'Concert',
            'conference' => 'Conférence',
            'atelier' => 'Atelier',
            'exposition' => 'Exposition'
        ];
        return $mapping[$slug] ?? 'Concert';
    }
    
    // Convertir le label en slug URL
    public static function getTypeSlugFromLabel($label)
    {
        $mapping = [
            'Concert' => 'concert',
            'Conférence' => 'conference',
            'Atelier' => 'atelier',
            'Exposition' => 'exposition'
        ];
        return $mapping[$label] ?? 'concert';
    }
    
    // Récupérer le nom de la colonne dans la table billet selon le type
    public static function getColumnFromType($type)
    {
        $mapping = [
            'concert' => 'idmanif_concert',
            'conference' => 'idmanif_conference',
            'atelier' => 'idmanif_atelier',
            'exposition' => 'idmanif_exposition'
        ];
        return $mapping[$type] ?? 'idmanif_concert';
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