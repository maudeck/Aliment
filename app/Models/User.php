<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['nom', 'email', 'genre_id', 'password', 'is_gold'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function createUser(array $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if ($this->insert($data)) {
            return $this->insertID();
        }

        return false;
    }

    public function getByEmail(string $email): ?array
    {
        $user = $this->where('email', $email)->first();

        return $user ?: null;
    }
}
