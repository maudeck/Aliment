<?php

namespace App\Models;

use CodeIgniter\Model;

class Genre extends Model
{
    protected $table = 'genres';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['nom'];

    protected $useTimestamps = false;
}