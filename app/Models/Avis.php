<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Avis extends Model
{
    protected $table = 'avis';
    protected $primaryKey = 'idavis';
    
    protected $fillable = [
        'idpersonne',
        'idmanif_concert',
        'idmanif_conference',
        'idmanif_atelier',
        'idmanif_exposition',
        'noteavis',
        'commantaireavis'
    ];

    public $timestamps = false;

    // Relation avec l'utilisateur
    public function spectateur()
    {
        return $this->belongsTo(User::class, 'idpersonne', 'id');
    }

    // Récupérer tous les avis pour une manifestation
    public static function getAvisForManifestation($idmanif, $type)
    {
        $column = self::getColumnFromType($type);
        
        return DB::table('avis')
            ->join('users', 'avis.idpersonne', '=', 'users.id')  // CORRIGÉ: users.id au lieu de users.idpersonne
            ->where($column, $idmanif)
            ->whereNotNull($column)
            ->select('avis.*', 'users.nom as user_name')  // CORRIGÉ: users.nom au lieu de users.name
            ->orderBy('avis.idavis', 'desc')
            ->get();
    }

    // Calculer la moyenne des notes
    public static function getAverageNote($idmanif, $type)
    {
        $column = self::getColumnFromType($type);
        
        $avg = DB::table('avis')
            ->where($column, $idmanif)
            ->whereNotNull($column)
            ->avg('noteavis');
        
        return $avg ? round($avg, 1) : 0;
    }

    // Compter le nombre d'avis
    public static function countAvis($idmanif, $type)
    {
        $column = self::getColumnFromType($type);
        
        return DB::table('avis')
            ->where($column, $idmanif)
            ->whereNotNull($column)
            ->count();
    }

    // Vérifier si un utilisateur a déjà donné un avis
    public static function userHasReviewed($idmanif, $userId, $type)
    {
        $column = self::getColumnFromType($type);
        
        return DB::table('avis')
            ->where($column, $idmanif)
            ->where('idpersonne', $userId)
            ->whereNotNull($column)
            ->exists();
    }

    // Vérifier si l'utilisateur a réservé cette manifestation
    public static function userHasReservation($idmanif, $userId, $type)
    {
        $column = self::getColumnFromType($type);
        
        return DB::table('billet')
            ->where($column, $idmanif)
            ->where('iduser', $userId)
            ->exists();
    }

    // Obtenir la répartition des notes
    public static function getNoteDistribution($idmanif, $type)
    {
        $column = self::getColumnFromType($type);
        
        return DB::table('avis')
            ->where($column, $idmanif)
            ->whereNotNull($column)
            ->select('noteavis as note', DB::raw('count(*) as count'))
            ->groupBy('noteavis')
            ->orderBy('noteavis', 'desc')
            ->pluck('count', 'note')
            ->toArray();
    }

    // Obtenir la colonne selon le type
    private static function getColumnFromType($type)
    {
        return match($type) {
            'concert' => 'idmanif_concert',
            'conference' => 'idmanif_conference',
            'atelier' => 'idmanif_atelier',
            'exposition' => 'idmanif_exposition',
            default => 'idmanif_concert'
        };
    }
}