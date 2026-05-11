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

    public function getAvecPrix(int $regimeId, int $dureeId = 3, bool $appliquerRemiseGold = false): ?array
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

        $prix = $row ? (float) $row['prix'] : null;

        if ($prix !== null && $appliquerRemiseGold) {
            $regime['prix_original'] = $prix;
            $prix = round($prix * 0.85);
        }

        $regime['prix'] = $prix !== null ? (int) round($prix) : null;

        return $regime;
    }

    public function upsertPrix(int $regimeId, int $dureeId, float $prix): void
    {
        $db = \Config\Database::connect();
        $table = $db->table('regime_prix');

        $exists = $table->where('regime_id', $regimeId)
            ->where('duree_id', $dureeId)
            ->get()
            ->getRowArray();

        if ($exists) {
            $table->where('regime_id', $regimeId)
                ->where('duree_id', $dureeId)
                ->update(['prix' => $prix]);
            return;
        }

        $table->insert([
            'regime_id' => $regimeId,
            'duree_id' => $dureeId,
            'prix' => $prix,
        ]);
    }
}
