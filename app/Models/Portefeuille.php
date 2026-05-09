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

    protected $useTimestamps = false;

    public function getSolde(int $userId): float
    {
        $row = $this->where('user_id', $userId)->first();
        return $row ? (float) $row['solde'] : 0;
    }
}