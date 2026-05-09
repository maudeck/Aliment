<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeObjectif extends Model
{
    protected $table = 'regime_objectifs';

    protected $returnType = 'array';

    protected $allowedFields = [
        'regime_id',
        'objectif_id'
    ];
}