<?php

namespace App\Controllers\SupplyRequests;

use App\Controllers\BaseController;
use App\Models\SupplyRequestModel;
use App\Models\ProductModel;
use App\Models\DepartmentModel;

class StaffRequestController extends BaseController
{
    protected SupplyRequestModel $requestModel;

    public function __construct()
    {
        $this->requestModel = new SupplyRequestModel();
        helper(['form', 'permission', 'format']);
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $data = [
            'title'    => 'My Requests',
            'requests' => $this->requestModel->getRequestsWithDetails(null, $userId),
        ];
        return view('supply_requests/staff_index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'New Supply Request',
            'products'    => (new ProductModel())->getProductsWithCategory(null, null, 'active'),
            'departments' => (new DepartmentModel())->findAll(),
        ];
        return view('supply_requests/create', $data);
    }

    public function store()
    {
        $rules = [
            'product_id'         => 'required|integer',
            'requested_quantity' => 'required|integer|greater_than[0]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->requestModel->insert([
            'request_code'       => $this->requestModel->generateRequestCode(),
            'user_id'            => session()->get('user_id'),
            'department_id'      => session()->get('department_id'),
            'product_id'         => $this->request->getPost('product_id'),
            'requested_quantity' => $this->request->getPost('requested_quantity'),
            'notes'              => $this->request->getPost('notes'),
            'status'             => 'pending',
            'requested_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/staff/requests')->with('success', 'Supply request submitted successfully.');
    }

    public function show(int $id)
    {
        $request = $this->requestModel->select('supply_requests.*, users.username as requester_name, departments.name as department_name, products.name as product_name, products.code as product_code, products.unit_of_measurement, approver.username as approver_name')
            ->join('users', 'users.id = supply_requests.user_id', 'left')
            ->join('departments', 'departments.id = supply_requests.department_id', 'left')
            ->join('products', 'products.id = supply_requests.product_id', 'left')
            ->join('users as approver', 'approver.id = supply_requests.approved_by', 'left')
            ->find($id);

        if (!$request || (int)$request['user_id'] !== (int)session()->get('user_id')) {
            return redirect()->to('/staff/requests')->with('error', 'Request not found.');
        }

        return view('supply_requests/show', ['title' => 'Request Details', 'request' => $request]);
    }
}
