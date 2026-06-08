<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SupplyRequestModel;

/**
 * StaffDashboardController
 * Dashboard for staff users with their own requests and inventory view.
 */
class StaffDashboardController extends BaseController
{
    public function __construct()
    {
        helper(['permission', 'format']);
    }

    /**
     * Staff dashboard.
     */
    public function index()
    {
        $requestModel = new SupplyRequestModel();
        $userId = session()->get('user_id');

        $myRequests = $requestModel->getRequestsWithDetails(null, $userId);

        // Count statuses for this user's requests
        $myCounts = ['pending' => 0, 'approved' => 0, 'released' => 0, 'rejected' => 0];
        foreach ($myRequests as $req) {
            $myCounts[$req['status']]++;
        }

        $data = [
            'title'          => 'My Dashboard',
            'myRequests'     => array_slice($myRequests, 0, 10),
            'pendingCount'   => $myCounts['pending'],
            'approvedCount'  => $myCounts['approved'],
            'releasedCount'  => $myCounts['released'],
            'rejectedCount'  => $myCounts['rejected'],
            'totalRequests'  => count($myRequests),
        ];

        return view('dashboard/staff', $data);
    }

    /**
     * Staff read-only inventory view.
     */
    public function inventory()
    {
        $productModel = new ProductModel();

        $data = [
            'title'    => 'Inventory',
            'products' => $productModel->getProductsWithCategory(),
        ];

        return view('dashboard/staff_inventory', $data);
    }
}
