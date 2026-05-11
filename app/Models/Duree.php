<?php

namespace App\Models;

use CodeIgniter\Model;

class Duree extends Model
{
    protected $table = 'durees';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['nom', 'nombre_jours'];

    protected $useTimestamps = false;
}