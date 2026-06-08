<?php

namespace App\Libraries;

use App\Models\ProductModel;
use App\Models\StockLogModel;

/**
 * Stock Service
 * Handles stock adjustments, deductions, and logging.
 */
class StockService
{
    protected ProductModel $productModel;
    protected StockLogModel $stockLogModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->stockLogModel = new StockLogModel();
    }

    /**
     * Add stock to a product.
     */
    public function addStock(int $productId, int $quantity, ?string $notes = null, ?int $referenceId = null): bool
    {
        $product = $this->productModel->find($productId);
        if (!$product) return false;

        $previousQty = (int) $product['quantity_in_stock'];
        $newQty = $previousQty + $quantity;

        $this->productModel->update($productId, ['quantity_in_stock' => $newQty]);

        $this->stockLogModel->insert([
            'product_id'        => $productId,
            'log_type'          => 'add',
            'quantity'          => $quantity,
            'previous_quantity' => $previousQty,
            'new_quantity'      => $newQty,
            'reference_id'      => $referenceId,
            'performed_by'      => session()->get('user_id'),
            'notes'             => $notes,
        ]);

        return true;
    }

    /**
     * Remove stock from a product (e.g., supply request release).
     */
    public function removeStock(int $productId, int $quantity, ?string $notes = null, ?int $referenceId = null): bool
    {
        $product = $this->productModel->find($productId);
        if (!$product) return false;

        $previousQty = (int) $product['quantity_in_stock'];
        $newQty = max(0, $previousQty - $quantity);

        $this->productModel->update($productId, ['quantity_in_stock' => $newQty]);

        $this->stockLogModel->insert([
            'product_id'        => $productId,
            'log_type'          => 'remove',
            'quantity'          => $quantity,
            'previous_quantity' => $previousQty,
            'new_quantity'      => $newQty,
            'reference_id'      => $referenceId,
            'performed_by'      => session()->get('user_id'),
            'notes'             => $notes,
        ]);

        return true;
    }

    /**
     * Adjust stock (manual correction).
     */
    public function adjustStock(int $productId, int $newQuantity, ?string $notes = null): bool
    {
        $product = $this->productModel->find($productId);
        if (!$product) return false;

        $previousQty = (int) $product['quantity_in_stock'];

        $this->productModel->update($productId, ['quantity_in_stock' => $newQuantity]);

        $this->stockLogModel->insert([
            'product_id'        => $productId,
            'log_type'          => 'adjust',
            'quantity'          => abs($newQuantity - $previousQty),
            'previous_quantity' => $previousQty,
            'new_quantity'      => $newQuantity,
            'performed_by'      => session()->get('user_id'),
            'notes'             => $notes ?? 'Manual stock adjustment',
        ]);

        return true;
    }
}
