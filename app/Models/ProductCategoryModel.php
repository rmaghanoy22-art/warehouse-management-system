<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Product Category Model
 */
class ProductCategoryModel extends Model
{
    protected $table            = 'product_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'name',
        'description',
    ];

    protected $validationRules = [
        'name' => 'required|max_length[100]',
    ];

    /**
     * Get categories with product count.
     */
    public function getCategoriesWithProductCount(): array
    {
        return $this->select('product_categories.*, COUNT(products.id) as product_count')
                    ->join('products', 'products.category_id = product_categories.id AND products.deleted_at IS NULL', 'left')
                    ->groupBy('product_categories.id')
                    ->findAll();
    }
}
