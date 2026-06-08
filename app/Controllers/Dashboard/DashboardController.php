<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SupplyRequestModel;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Models\StockLogModel;
use App\Models\AuditLogModel;

/**
 * DashboardController
 * Admin/Warehouse dashboard with analytics.
 */
class DashboardController extends BaseController
{
    public function __construct()
    {
        helper(['permission', 'format']);
    }

    /**
     * Admin dashboard.
     */
    public function index()
    {
        $productModel  = new ProductModel();
        $requestModel  = new SupplyRequestModel();
        $userModel     = new UserModel();
        $deptModel     = new DepartmentModel();
        $stockLogModel = new StockLogModel();
        $auditModel    = new AuditLogModel();

        $statusCounts = $requestModel->getStatusCounts();

        $data = [
            'title'           => 'Dashboard',
            'totalProducts'   => $productModel->where('status', 'active')->countAllResults(),
            'totalUsers'      => $userModel->where('status', 'active')->countAllResults(),
            'totalDepartments'=> $deptModel->countAllResults(),
            'pendingRequests' => $statusCounts['pending'],
            'approvedRequests'=> $statusCounts['approved'],
            'releasedRequests'=> $statusCounts['released'],
            'rejectedRequests'=> $statusCounts['rejected'],
            'lowStockProducts'=> $productModel->getLowStockProducts(),
            'expiringProducts'=> $productModel->getExpiringProducts(),
            'recentRequests'  => $requestModel->getRequestsWithDetails(null, null),
            'recentLogs'      => $stockLogModel->getLogsWithDetails(),
            'recentAudit'     => $auditModel->getLogsWithUser(),
        ];

        // Limit arrays for dashboard display
        $data['recentRequests'] = array_slice($data['recentRequests'], 0, 10);
        $data['recentLogs']     = array_slice($data['recentLogs'], 0, 10);
        $data['recentAudit']    = array_slice($data['recentAudit'], 0, 10);

        return view('dashboard/admin', $data);
    }

    /**
     * Audit logs page.
     */
    public function auditLogs()
    {
        $auditModel = new AuditLogModel();

        $entityType = $this->request->getGet('entity_type');
        $action     = $this->request->getGet('action');

        $data = [
            'title' => 'Audit Logs',
            'logs'  => $auditModel->getLogsWithUser($entityType, $action),
            'currentEntityType' => $entityType,
            'currentAction'     => $action,
        ];

        return view('audit/index', $data);
    }
}
