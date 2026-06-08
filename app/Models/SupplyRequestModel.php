<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Supply Request Model
 * Handles supply request CRUD and workflow queries.
 */
class SupplyRequestModel extends Model
{
    protected $table            = 'supply_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'request_code',
        'user_id',
        'department_id',
        'product_id',
        'requested_quantity',
        'approved_quantity',
        'status',
        'notes',
        'feedback',
        'requested_at',
        'approved_at',
        'approved_by',
        'released_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected $validationRules = [
        'user_id'            => 'required|integer',
        'department_id'      => 'permit_empty|integer', // Allow null for staff without department assignment
        'product_id'         => 'required|integer',
        'requested_quantity' => 'required|integer|greater_than[0]',
    ];

    /**
     * Get requests with related data (user, department, product).
     */
    public function getRequestsWithDetails(?string $status = null, ?int $userId = null): array
    {
        $builder = $this->select('supply_requests.*, 
                    users.username as requester_name,
                    departments.name as department_name,
                    products.name as product_name,
                    products.code as product_code,
                    products.unit_of_measurement,
                    approver.username as approver_name')
                    ->join('users', 'users.id = supply_requests.user_id', 'left')
                    ->join('departments', 'departments.id = supply_requests.department_id', 'left')
                    ->join('products', 'products.id = supply_requests.product_id', 'left')
                    ->join('users as approver', 'approver.id = supply_requests.approved_by', 'left');

        if ($status) {
            $builder->where('supply_requests.status', $status);
        }

        if ($userId) {
            $builder->where('supply_requests.user_id', $userId);
        }

        return $builder->orderBy('supply_requests.created_at', 'DESC')->findAll();
    }

    /**
     * Generate a unique request code.
     */
    public function generateRequestCode(): string
    {
        $year = date('Y');
        $lastRequest = $this->like('request_code', "WMS-{$year}-", 'after')
                            ->orderBy('id', 'DESC')
                            ->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest['request_code'], -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('WMS-%s-%04d', $year, $nextNumber);
    }

    /**
     * Get request counts by status.
     */
    public function getStatusCounts(): array
    {
        $results = $this->select('status, COUNT(*) as count')
                        ->groupBy('status')
                        ->findAll();

        $counts = ['pending' => 0, 'approved' => 0, 'released' => 0, 'rejected' => 0];
        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['count'];
        }
        return $counts;
    }
}
