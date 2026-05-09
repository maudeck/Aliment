<?php

namespace App\Models;

use CodeIgniter\Model;

class Portefeuille extends Model
{
    protected $table      = 'portefeuilles';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'user_id',
        'solde',
    ];

    // La table a seulement created_at (pas updated_at)
    protected $useTimestamps = false;

    // ── Solde ─────────────────────────────────────────────────────

    public function getSolde(int $userId): float
    {
        $row = $this->where('user_id', $userId)->first();
        return $row ? (float) $row['solde'] : 0;
    }

    // ── Recharge via code ─────────────────────────────────────────

    /**
     * Valide et applique un code de recharge.
     * Retourne ['success' => bool, 'message' => string, 'montant' => float]
     */
    public function utiliserCode(string $code, int $userId): array
    {
        $code = strtoupper(trim($code));
        $db   = \Config\Database::connect();

        // 1. Vérifier que le code existe dans codes_recharge
        $codeRow = $db->table('codes_recharge')
                      ->where('code', $code)
                      ->get()
                      ->getRowArray();

        if (!$codeRow) {
            return ['success' => false, 'message' => 'Code invalide. Vérifiez et réessayez.', 'montant' => 0];
        }

        if ($codeRow['est_utilise']) {
            return ['success' => false, 'message' => 'Ce code a déjà été utilisé.', 'montant' => 0];
        }

        $montant = (float) $codeRow['montant'];

        // 2. Transaction : marquer le code utilisé + créditer + historique
        $db->transStart();
        $portefeuille = $db->table('portefeuilles')
                          ->where('user_id', $userId)
                          ->get()
                          ->getRowArray();

        if (!$portefeuille) {
            $db->table('portefeuilles')->insert([
                'user_id' => $userId,
                'solde'   => 0,
            ]);
        }

        // Marquer le code comme utilisé
        $db->table('codes_recharge')
           ->where('id', $codeRow['id'])
           ->update(['est_utilise' => true]);

        // Créditer le portefeuille (incrément SQL direct pour éviter les race conditions)
        $db->table('portefeuilles')
           ->where('user_id', $userId)
           ->set('solde', "solde + {$montant}", false)
           ->update();

        // Insérer dans l'historique recharge_portefeuille
        $db->table('recharge_portefeuille')->insert([
            'user_id'    => $userId,
            'code_id'    => $codeRow['id'],
            'montant'    => $montant,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return ['success' => false, 'message' => 'Une erreur est survenue. Réessayez.', 'montant' => 0];
        }

        return [
            'success' => true,
            'message' => 'Portefeuille rechargé avec succès !',
            'montant' => $montant,
        ];
    }

    // ── Historique des recharges ──────────────────────────────────

    /**
     * Retourne les dernières recharges de l'utilisateur.
     * Joint codes_recharge pour afficher le code utilisé.
     */
    public function getHistorique(int $userId, int $limit = 10): array
    {
        $db = \Config\Database::connect();

        return $db->table('recharge_portefeuille rp')
                  ->select('rp.montant, rp.created_at, cr.code')
                  ->join('codes_recharge cr', 'cr.id = rp.code_id')
                  ->where('rp.user_id', $userId)
                  ->orderBy('rp.created_at', 'DESC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
    }
}