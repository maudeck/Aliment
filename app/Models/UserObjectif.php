<?php

namespace App\Models;

use CodeIgniter\Model;

class UserObjectif extends Model
{
    protected $table = 'user_objectifs';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['user_id', 'objectif_id'];

    protected $useTimestamps = false;
}
