<?php

namespace App\Controllers\Stock;

use App\Controllers\BaseController;
use App\Models\StockLogModel;
use App\Models\ProductModel;
use App\Libraries\StockService;
use App\Libraries\AuditService;

class StockLogController extends BaseController
{
    protected StockLogModel $stockLogModel;
    protected StockService $stockService;

    public function __construct()
    {
        $this->stockLogModel = new StockLogModel();
        $this->stockService  = new StockService();
        helper(['form', 'permission', 'format']);
    }

    public function index()
    {
        $productId = $this->request->getGet('product_id');
        $logType   = $this->request->getGet('log_type');

        $data = [
            'title'          => 'Stock Logs',
            'logs'           => $this->stockLogModel->getLogsWithDetails($productId ? (int)$productId : null, $logType),
            'products'       => (new ProductModel())->orderBy('name')->findAll(),
            'currentProduct' => $productId,
            'currentType'    => $logType,
        ];
        return view('stock/logs', $data);
    }

    public function adjustForm()
    {
        $data = [
            'title'    => 'Stock Adjustment',
            'products' => (new ProductModel())->getProductsWithCategory(null, null, 'active'),
        ];
        return view('stock/adjust', $data);
    }

    public function adjust()
    {
        $rules = [
            'product_id'   => 'required|integer',
            'new_quantity'  => 'required|integer|greater_than_equal_to[0]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId   = (int) $this->request->getPost('product_id');
        $newQuantity = (int) $this->request->getPost('new_quantity');
        $notes       = $this->request->getPost('notes');

        $this->stockService->adjustStock($productId, $newQuantity, $notes);

        (new AuditService())->logCustom('stock_adjust', 'product', $productId, [
            'new_quantity' => $newQuantity,
            'notes' => $notes,
        ]);

        return redirect()->to('/stock/logs')->with('success', 'Stock adjusted successfully.');
    }
}
