<?php

namespace App\Controllers\SupplyRequests;

use App\Controllers\BaseController;
use App\Models\SupplyRequestModel;
use App\Models\ProductModel;
use App\Libraries\AuditService;
use App\Libraries\StockService;

class SupplyRequestController extends BaseController
{
    protected SupplyRequestModel $requestModel;
    protected AuditService $auditService;
    protected StockService $stockService;

    public function __construct()
    {
        $this->requestModel = new SupplyRequestModel();
        $this->auditService = new AuditService();
        $this->stockService = new StockService();
        helper(['form', 'permission', 'format']);
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $data = [
            'title'         => 'Supply Requests',
            'requests'      => $this->requestModel->getRequestsWithDetails($status),
            'statusCounts'  => $this->requestModel->getStatusCounts(),
            'currentStatus' => $status,
        ];
        return view('supply_requests/index', $data);
    }

    public function show(int $id)
    {
        $request = $this->requestModel
            ->select('supply_requests.*, 
                users.username as requester_name,
                departments.name as department_name,
                products.name as product_name,
                products.code as product_code,
                products.quantity_in_stock,
                products.unit_of_measurement,
                approver.username as approver_name')
            ->join('users', 'users.id = supply_requests.user_id', 'left')
            ->join('departments', 'departments.id = supply_requests.department_id', 'left')
            ->join('products', 'products.id = supply_requests.product_id', 'left')
            ->join('users as approver', 'approver.id = supply_requests.approved_by', 'left')
            ->find($id);

        if (!$request) {
            return redirect()->to('/supply-requests')->with('error', 'Request not found.');
        }

        return view('supply_requests/show', [
            'title'   => 'Request Details - ' . $request['request_code'],
            'request' => $request,
        ]);
    }

    public function approve(int $id)
    {
        $request = $this->requestModel->find($id);
        if (!$request || $request['status'] !== 'pending') {
            return redirect()->to('/supply-requests')->with('error', 'Request cannot be approved.');
        }

        $approvedQty = $this->request->getPost('approved_quantity') ?: $request['requested_quantity'];
        $feedback    = $this->request->getPost('feedback');

        $this->requestModel->update($id, [
            'status'            => 'approved',
            'approved_quantity' => $approvedQty,
            'approved_by'       => session()->get('user_id'),
            'approved_at'       => date('Y-m-d H:i:s'),
            'feedback'          => $feedback,
        ]);

        $this->auditService->logCustom('approve', 'supply_request', $id, [
            'approved_quantity' => $approvedQty,
        ]);

        return redirect()->to('/supply-requests')->with('success', 'Request approved successfully.');
    }

    public function reject(int $id)
    {
        $request = $this->requestModel->find($id);
        if (!$request || $request['status'] !== 'pending') {
            return redirect()->to('/supply-requests')->with('error', 'Request cannot be rejected.');
        }

        $reason = $this->request->getPost('rejection_reason');

        $this->requestModel->update($id, [
            'status'           => 'rejected',
            'rejected_at'      => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason,
            'approved_by'      => session()->get('user_id'),
        ]);

        $this->auditService->logCustom('reject', 'supply_request', $id, ['reason' => $reason]);

        return redirect()->to('/supply-requests')->with('success', 'Request rejected.');
    }

    public function release(int $id)
    {
        $request = $this->requestModel->find($id);
        if (!$request || $request['status'] !== 'approved') {
            return redirect()->to('/supply-requests')->with('error', 'Request cannot be released.');
        }

        $releaseQty = $request['approved_quantity'] ?? $request['requested_quantity'];

        $this->stockService->removeStock(
            $request['product_id'],
            $releaseQty,
            "Released for supply request {$request['request_code']}",
            $id
        );

        $this->requestModel->update($id, [
            'status'      => 'released',
            'released_at' => date('Y-m-d H:i:s'),
        ]);

        $this->auditService->logCustom('release', 'supply_request', $id, [
            'quantity_released' => $releaseQty,
        ]);

        return redirect()->to('/supply-requests')->with('success', 'Request released. Stock has been deducted.');
    }
}
