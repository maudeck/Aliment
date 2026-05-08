<?php

namespace App\Models;

use CodeIgniter\Model;

class Objectif extends Model
{
    protected $table = 'objectifs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['nom', 'description'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
