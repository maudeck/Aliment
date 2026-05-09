<?php

namespace App\Models;

use CodeIgniter\Model;

class Regime extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'description',
        'variation_poids',
        'pourcentage_viande',
        'pourcentage_poisson',
        'pourcentage_volaille'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAvecPrix(int $regimeId, int $dureeId = 3): ?array
    {
        $regime = $this->find($regimeId);

        if (!$regime) {
            return null;
        }

        $db  = \Config\Database::connect();
        $row = $db->table('regime_prix')
                  ->where('regime_id', $regimeId)
                  ->where('duree_id', $dureeId)
                  ->get()
                  ->getRowArray();

        $regime['prix'] = $row ? (int) $row['prix'] : null;

        return $regime;
    }
}
