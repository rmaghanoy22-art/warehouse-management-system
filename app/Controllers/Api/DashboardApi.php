<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SupplyRequestModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;

class DashboardApi extends BaseController
{
    public function stats()
    {
        $productModel = new ProductModel();
        $requestModel = new SupplyRequestModel();

        $statusCounts = $requestModel->getStatusCounts();

        // Monthly request data for charts
        $monthlyData = $requestModel->select("DATE_FORMAT(created_at, '%Y-%m') as month, status, COUNT(*) as count")
            ->where('created_at >=', date('Y-01-01'))
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m'), status")
            ->findAll();

        // Top requested products
        $topProducts = $requestModel->select('products.name, COUNT(*) as request_count')
            ->join('products', 'products.id = supply_requests.product_id')
            ->groupBy('supply_requests.product_id')
            ->orderBy('request_count', 'DESC')
            ->limit(5)
            ->findAll();

        return $this->response->setJSON([
            'statusCounts' => $statusCounts,
            'monthlyData'  => $monthlyData,
            'topProducts'  => $topProducts,
            'totalProducts' => $productModel->where('status', 'active')->countAllResults(),
            'lowStock'      => count($productModel->getLowStockProducts()),
            'expiringSoon'  => count($productModel->getExpiringProducts(30)),
        ]);
    }
}
