<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Stock Log Model
 * Tracks all stock movements with complete audit trail.
 */
class StockLogModel extends Model
{
    protected $table            = 'stock_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    protected $allowedFields = [
        'product_id',
        'log_type',
        'quantity',
        'previous_quantity',
        'new_quantity',
        'reference_id',
        'performed_by',
        'notes',
    ];

    /**
     * Get logs with product and user details.
     */
    public function getLogsWithDetails(?int $productId = null, ?string $logType = null): array
    {
        $builder = $this->select('stock_logs.*, products.name as product_name, products.code as product_code, users.username as performed_by_name')
                        ->join('products', 'products.id = stock_logs.product_id', 'left')
                        ->join('users', 'users.id = stock_logs.performed_by', 'left');

        if ($productId) {
            $builder->where('stock_logs.product_id', $productId);
        }

        if ($logType) {
            $builder->where('stock_logs.log_type', $logType);
        }

        return $builder->orderBy('stock_logs.created_at', 'DESC')->findAll();
    }
}
