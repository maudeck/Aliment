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

    // La table a created_at mais pas updated_at
    protected $useTimestamps   = true;
    protected $createdField    = 'created_at';
    protected $updatedField    = '';   // pas de updated_at dans la table

    /**
     * Vérifie si un utilisateur a déjà acheté un régime.
     */
    public function aAchete(int $userId, int $regimeId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('regime_id', $regimeId)
                    ->first() !== null;
    }

    /**
     * Retourne tous les régimes achetés par un utilisateur,
     * avec le nom du régime, la durée et la date d'achat.
     */
    public function getRegimesAchetes(int $userId): array
    {
        $db = \Config\Database::connect();

        return $db->table('user_regimes ur')
                  ->select('
                      ur.id           AS achat_id,
                      ur.prix_paye,
                      ur.created_at   AS date_achat,
                      r.id            AS regime_id,
                      r.nom           AS regime_nom,
                      r.description   AS regime_description,
                      r.variation_poids,
                      r.pourcentage_viande,
                      r.pourcentage_poisson,
                      r.pourcentage_volaille,
                      d.nom           AS duree_nom,
                      d.nombre_jours
                  ')
                  ->join('regimes r', 'r.id = ur.regime_id')
                  ->join('durees d',  'd.id = ur.duree_id')
                  ->where('ur.user_id', $userId)
                  ->orderBy('ur.created_at', 'DESC')
                  ->get()
                  ->getResultArray();
    }
}