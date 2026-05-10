<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'password',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

   
    public function verifyPassword(string $password): bool
    {
   
        $admin = $this->first();

        if (!$admin) {
            return false;
        }

        return password_verify($password, $admin['password']);
    }


    public function createAdmin(string $username, string $password): bool|int
    {
        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        return $this->insert($data) ? $this->insertID() : false;
    }
}
