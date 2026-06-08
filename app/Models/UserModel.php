<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * User Model
 * Handles user CRUD, authentication lookups, and validation.
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'username',
        'email',
        'password',
        'role',
        'department_id',
        'status',
        'last_login',
        'google_id',
        'oauth_provider',
        'oauth_token',
        'profile_picture',
    ];

    protected $validationRules      = [];
    protected $validationMessages   = [];

    /**
     * Find user by username or email for login.
     */
    public function findByLogin(string $login): ?array
    {
        return $this->where('username', $login)
                    ->orWhere('email', $login)
                    ->first();
    }

    /**
     * Get users with their department info.
     */
    public function getUsersWithDepartment(): array
    {
        return $this->select('users.*, departments.name as department_name')
                    ->join('departments', 'departments.id = users.department_id', 'left')
                    ->findAll();
    }

    /**
     * Hash password before insert.
     */
    protected function beforeInsert(array $data): array
    {
        return $this->hashPassword($data);
    }

    /**
     * Hash password before update if changed.
     */
    protected function beforeUpdate(array $data): array
    {
        return $this->hashPassword($data);
    }

    protected $beforeInsert = ['hashPasswordCallback'];
    protected $beforeUpdate = ['hashPasswordCallback'];

    protected function hashPasswordCallback(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    private function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }
}
