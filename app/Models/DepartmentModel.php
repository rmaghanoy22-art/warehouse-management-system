<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Department Model
 */
class DepartmentModel extends Model
{
    protected $table            = 'departments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'name',
        'code',
        'description',
    ];

    protected $validationRules      = [];

    /**
     * Get department with user count.
     */
    public function getDepartmentsWithUserCount(): array
    {
        return $this->select('departments.*, COUNT(users.id) as user_count')
                    ->join('users', 'users.department_id = departments.id', 'left')
                    ->groupBy('departments.id')
                    ->findAll();
    }
}
