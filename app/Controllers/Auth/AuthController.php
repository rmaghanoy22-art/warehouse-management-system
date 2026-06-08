<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Libraries\AuditService;

/**
 * AuthController
 * Handles login, logout, and session management.
 */
class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected AuditService $auditService;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->auditService = new AuditService();
        helper(['form', 'permission', 'format']);
    }

    /**
     * Show login page.
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        return view('auth/login');
    }

    /**
     * Process login attempt.
     */
    public function attemptLogin()
    {
        $rules = [
            'login'    => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill in all fields.');
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByLogin($login);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->auditService->logCustom('login_failed', 'auth', null, ['login' => $login]);
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }

        if ($user['status'] !== 'active') {
            return redirect()->back()->with('error', 'Your account has been deactivated. Contact an administrator.');
        }

        // Set session data
        session()->set([
            'user_id'       => $user['id'],
            'username'      => $user['username'],
            'email'         => $user['email'],
            'user_role'     => $user['role'],
            'department_id' => $user['department_id'],
            'user_status'   => $user['status'],
            'isLoggedIn'    => true,
        ]);

        // Update last login
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Log successful login
        $this->auditService->logCustom('login', 'auth', $user['id'], ['username' => $user['username']]);

        return $this->redirectByRole();
    }

    /**
     * Logout user.
     */
    public function logout()
    {
        $this->auditService->logCustom('logout', 'auth', session()->get('user_id'));
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show registration page.
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        $departments = (new \App\Models\DepartmentModel())->findAll();
        return view('auth/register', ['departments' => $departments]);
    }

    /**
     * Process registration attempt.
     */
    public function attemptRegister()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'department_id'    => 'required|integer',
        ];

        $messages = [
            'username' => [
                'is_unique' => 'This username is already taken.',
            ],
            'email' => [
                'is_unique' => 'This email address is already registered.',
            ],
            'confirm_password' => [
                'matches' => 'Passwords do not match.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password'      => $this->request->getPost('password'),
            'role'          => 'staff',
            'department_id' => $this->request->getPost('department_id'),
            'status'        => 'active',
        ];

        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();

        $this->auditService->logCustom('register', 'auth', $userId, ['username' => $userData['username']]);

        // Auto-login after registration
        session()->set([
            'user_id'       => $userId,
            'username'      => $userData['username'],
            'email'         => $userData['email'],
            'user_role'     => 'staff',
            'department_id' => $userData['department_id'],
            'user_status'   => 'active',
            'isLoggedIn'    => true,
        ]);

        return redirect()->to('/staff/dashboard')->with('success', 'Registration successful! Welcome to WMS.');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectByRole()
    {
        $role = session()->get('user_role');
        return match ($role) {
            'admin', 'warehouse' => redirect()->to('/dashboard'),
            default              => redirect()->to('/staff/dashboard'),
        };
    }
}
