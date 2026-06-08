<?php

namespace App\Controllers\Departments;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Libraries\AuditService;

/**
 * DepartmentController
 * Full CRUD for departments.
 */
class DepartmentController extends BaseController
{
    protected DepartmentModel $departmentModel;
    protected AuditService $auditService;

    public function __construct()
    {
        $this->departmentModel = new DepartmentModel();
        $this->auditService    = new AuditService();
        helper(['form', 'permission', 'format']);
    }

    public function index()
    {
        $data = [
            'title'       => 'Departments',
            'departments' => $this->departmentModel->getDepartmentsWithUserCount(),
        ];
        return view('departments/index', $data);
    }

    public function create()
    {
        return view('departments/create', ['title' => 'Add Department']);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'code' => 'required|max_length[20]|is_unique[departments.code]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $deptData = [
            'name'        => $this->request->getPost('name'),
            'code'        => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
        ];

        $this->departmentModel->insert($deptData);
        $this->auditService->logCreate('department', $this->departmentModel->getInsertID(), $deptData);

        return redirect()->to('/departments')->with('success', 'Department created successfully.');
    }

    public function edit(int $id)
    {
        $dept = $this->departmentModel->find($id);
        if (!$dept) return redirect()->to('/departments')->with('error', 'Department not found.');

        return view('departments/edit', ['title' => 'Edit Department', 'department' => $dept]);
    }

    public function update(int $id)
    {
        $dept = $this->departmentModel->find($id);
        if (!$dept) return redirect()->to('/departments')->with('error', 'Department not found.');

        $rules = [
            'name' => 'required|max_length[100]',
            'code' => "required|max_length[20]|is_unique[departments.code,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name'        => $this->request->getPost('name'),
            'code'        => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
        ];

        $this->auditService->logUpdate('department', $id, $dept, $updateData);
        $this->departmentModel->update($id, $updateData);

        return redirect()->to('/departments')->with('success', 'Department updated successfully.');
    }

    public function delete(int $id)
    {
        $dept = $this->departmentModel->find($id);
        if (!$dept) return redirect()->to('/departments')->with('error', 'Department not found.');

        $this->auditService->logDelete('department', $id, $dept);
        $this->departmentModel->delete($id);

        return redirect()->to('/departments')->with('success', 'Department deleted successfully.');
    }
}
