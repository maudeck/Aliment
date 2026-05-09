<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRegime extends Model
{
    protected $table      = 'user_regimes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'user_id',
        'regime_id',
        'duree_id',
        'prix_paye',
    ];

    protected $useTimestamps = false;

    // Vérifie si un utilisateur a déjà acheté un régime.
    
    public function aAchete(int $userId, int $regimeId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('regime_id', $regimeId)
                    ->first() !== null;
    }

    
    public function getRegimesAchetes(int $userId): array
    {
        $db = \Config\Database::connect();

        return $db->table('user_regimes')
                  ->select('
                      user_regimes.id AS achat_id,
                      user_regimes.prix_paye,
                      user_regimes.created_at AS date_achat,
                      regimes.id AS regime_id,
                      regimes.nom AS regime_nom,
                      regimes.description AS regime_description,
                      regimes.variation_poids,
                      regimes.pourcentage_viande,
                      regimes.pourcentage_poisson,
                      regimes.pourcentage_volaille,
                      durees.nom AS duree_nom,
                      durees.nombre_jours
                  ')
                  ->join('regimes', 'regimes.id = user_regimes.regime_id')
                  ->join('durees',  'durees.id  = user_regimes.duree_id')
                  ->where('user_regimes.user_id', $userId)
                  ->orderBy('user_regimes.created_at', 'DESC')
                  ->get()
                  ->getResultArray();
    }
}