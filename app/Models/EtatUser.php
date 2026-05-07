<?php

namespace App\Models;

use CodeIgniter\Model;

class EtatUser extends Model
{
    protected $table = 'etat_user';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['user_id', 'taille', 'poids', 'objectif'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
