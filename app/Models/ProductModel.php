<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Product Model
 * Manages product CRUD with soft deletes and category relationships.
 */
class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $allowedFields = [
        'code',
        'name',
        'category_id',
        'unit_of_measurement',
        'quantity_in_stock',
        'reorder_level',
        'expiration_date',
        'status',
        'description',
    ];

    protected $validationRules      = [];

    /**
     * Get products with category names.
     */
    public function getProductsWithCategory(?string $search = null, ?string $category = null, ?string $status = null): array
    {
        $builder = $this->select('products.*, product_categories.name as category_name')
                        ->join('product_categories', 'product_categories.id = products.category_id', 'left');

        if ($search) {
            $builder->groupStart()
                    ->like('products.name', $search)
                    ->orLike('products.code', $search)
                    ->groupEnd();
        }

        if ($category) {
            $builder->where('products.category_id', $category);
        }

        if ($status) {
            $builder->where('products.status', $status);
        }

        return $builder->orderBy('products.name', 'ASC')->findAll();
    }

    /**
     * Get low stock products (below reorder level).
     */
    public function getLowStockProducts(): array
    {
        return $this->select('products.*, product_categories.name as category_name')
                    ->join('product_categories', 'product_categories.id = products.category_id', 'left')
                    ->where('products.quantity_in_stock <= products.reorder_level')
                    ->where('products.status', 'active')
                    ->findAll();
    }

    /**
     * Get expiring products (within 30 days or already expired).
     */
    public function getExpiringProducts(int $daysThreshold = 30): array
    {
        $thresholdDate = date('Y-m-d', strtotime("+{$daysThreshold} days"));

        return $this->select('products.*, product_categories.name as category_name')
                    ->join('product_categories', 'product_categories.id = products.category_id', 'left')
                    ->where('products.expiration_date IS NOT NULL')
                    ->where('products.expiration_date <=', $thresholdDate)
                    ->where('products.status', 'active')
                    ->orderBy('products.expiration_date', 'ASC')
                    ->findAll();
    }
}
