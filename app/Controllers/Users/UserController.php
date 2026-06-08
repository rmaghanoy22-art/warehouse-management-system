<?php

namespace App\Controllers\Users;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DepartmentModel;
use App\Libraries\AuditService;

class UserController extends BaseController
{
    protected UserModel $userModel;
    protected AuditService $auditService;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->auditService = new AuditService();
        helper(['form', 'permission', 'format']);
    }

    public function index()
    {
        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->getUsersWithDepartment(),
        ];
        return view('users/index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'Add User',
            'departments' => (new DepartmentModel())->findAll(),
        ];
        return view('users/create', $data);
    }

    public function store()
    {
        $rules = [
            'username'      => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'         => 'required|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[8]',
            'role'          => 'required|in_list[staff,admin,warehouse]',
            'department_id' => 'required|integer',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password'      => $this->request->getPost('password'),
            'role'          => $this->request->getPost('role'),
            'department_id' => $this->request->getPost('department_id'),
            'status'        => $this->request->getPost('status') ?? 'active',
        ];

        $this->userModel->insert($userData);
        $this->auditService->logCreate('user', $this->userModel->getInsertID(), ['username' => $userData['username'], 'role' => $userData['role']]);

        return redirect()->to('/users')->with('success', 'User created successfully.');
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('/users')->with('error', 'User not found.');

        $data = [
            'title'       => 'Edit User',
            'user'        => $user,
            'departments' => (new DepartmentModel())->findAll(),
        ];
        return view('users/edit', $data);
    }

    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('/users')->with('error', 'User not found.');

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'     => 'required|in_list[staff,admin,warehouse]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'role'          => $this->request->getPost('role'),
            'department_id' => $this->request->getPost('department_id'),
            'status'        => $this->request->getPost('status'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        $this->auditService->logUpdate('user', $id, $user, $updateData);
        $this->userModel->update($id, $updateData);

        return redirect()->to('/users')->with('success', 'User updated successfully.');
    }

    public function delete(int $id)
    {
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account.');
        }
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('/users')->with('error', 'User not found.');

        $this->auditService->logDelete('user', $id, $user);
        $this->userModel->delete($id);

        return redirect()->to('/users')->with('success', 'User deleted successfully.');
    }
}
