<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteSportive extends Model
{
    protected $table      = 'activites_sportives';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'nom',
        'description',
        'calories_brulees_heure',
    ];

    protected $useTimestamps = false;

    /**
     * Retourne toutes les activités liées à un régime donné.
     */
    public function getByRegime(int $regimeId): array
    {
        $db = \Config\Database::connect();

        return $db->table('activites_sportives')
                  ->select('activites_sportives.*')
                  ->join('regime_activites', 'regime_activites.activite_id = activites_sportives.id')
                  ->where('regime_activites.regime_id', $regimeId)
                  ->get()
                  ->getResultArray();
    }
}