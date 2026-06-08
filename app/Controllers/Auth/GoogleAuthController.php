<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Libraries\AuditService;
use App\Libraries\GoogleClient;
use App\Libraries\GoogleOauth2;

class GoogleAuthController extends BaseController
{
    protected UserModel $userModel;
    protected AuditService $auditService;
    protected GoogleClient $googleClient;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->auditService = new AuditService();
        helper(['form', 'permission', 'format']);

        // Initialize Google Client
        $this->googleClient = new GoogleClient();
        
        // Load settings from .env
        $clientId = env('google.clientID') ?: env('google_clientID') ?: '';
        $clientSecret = env('google.clientSecret') ?: env('google_clientSecret') ?: '';
        $redirectUri = env('google.redirectURI') ?: env('google_redirectURI') ?: '';

        $this->googleClient->setClientId($clientId);
        $this->googleClient->setClientSecret($clientSecret);
        $this->googleClient->setRedirectUri($redirectUri);
        
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    /**
     * Redirect to Google Auth URL
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        $authUrl = $this->googleClient->createAuthUrl();
        return redirect()->to($authUrl);
    }

    /**
     * Handle Google Callback
     */
    public function callback()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        $code = $this->request->getGet('code');
        if (empty($code)) {
            return redirect()->to('/login')->with('error', 'Google authentication failed: Authorization code missing.');
        }

        try {
            // Fetch token
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                log_message('error', 'Google OAuth Error: ' . json_encode($token));
                return redirect()->to('/login')->with('error', 'Google authentication failed: ' . ($token['error_description'] ?? $token['error']));
            }

            $this->googleClient->setAccessToken($token);

            // Get Google User Info
            $googleOauth = new GoogleOauth2($this->googleClient);
            $userInfo = $googleOauth->userinfo->get();

            $googleId  = $userInfo->id;
            $email     = $userInfo->email;
            $name      = $userInfo->name;
            $picture   = $userInfo->picture;

            // 1. Check if user already exists by google_id
            $user = $this->userModel->where('google_id', $googleId)->first();

            // 2. If not found by google_id, check by email
            if (!$user) {
                $userByEmail = $this->userModel->where('email', $email)->first();
                if ($userByEmail) {
                    // Link Google account to existing local account
                    $updateData = [
                        'google_id'       => $googleId,
                        'oauth_provider'  => 'google',
                        'profile_picture' => $picture,
                        'oauth_token'     => json_encode($token),
                    ];
                    $this->userModel->update($userByEmail['id'], $updateData);
                    $user = $this->userModel->find($userByEmail['id']);
                }
            }

            // 3. If still not found, auto-create a new user (default to staff)
            if (!$user) {
                // Generate a unique username from email prefix
                $emailPrefix = explode('@', $email)[0];
                $username = preg_replace('/[^a-zA-Z0-9]/', '', $emailPrefix);
                
                // Ensure username is unique
                $baseUsername = $username;
                $counter = 1;
                while ($this->userModel->where('username', $username)->first()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $userData = [
                    'username'            => $username,
                    'email'               => $email,
                    'password'            => null, // Null password for OAuth users
                    'role'                => 'staff',
                    'department_id'       => null, // Assignment happens later
                    'status'              => 'active',
                    'google_id'           => $googleId,
                    'oauth_provider'      => 'google',
                    'oauth_token'         => json_encode($token),
                    'profile_picture'     => $picture,
                ];

                $this->userModel->insert($userData);
                $userId = $this->userModel->getInsertID();
                $user = $this->userModel->find($userId);

                $this->auditService->logCreate('user', $userId, ['username' => $username, 'role' => 'staff', 'oauth_signup' => true]);
            } else {
                // Update access token on subsequent logins
                $this->userModel->update($user['id'], [
                    'oauth_token'     => json_encode($token),
                    'profile_picture' => $picture
                ]);
            }

            if ($user['status'] !== 'active') {
                return redirect()->to('/login')->with('error', 'Your account has been deactivated. Contact an administrator.');
            }

            // If new user has no department assigned, ask them to select one
            if (is_null($user['department_id'])) {
                session()->set([
                    'oauth_temp_user_id'  => $user['id'],
                    'oauth_temp_username' => $user['username'],
                    'oauth_temp_email'    => $user['email'],
                    'oauth_temp_role'     => $user['role'],
                    'oauth_temp_profile'  => $user['profile_picture'],
                ]);
                return redirect()->to('/auth/google/select-department');
            }

            // Set login session
            session()->set([
                'user_id'         => $user['id'],
                'username'        => $user['username'],
                'email'           => $user['email'],
                'user_role'       => $user['role'],
                'department_id'   => $user['department_id'],
                'user_status'     => $user['status'],
                'profile_picture' => $user['profile_picture'],
                'isLoggedIn'      => true,
            ]);

            // Update last login timestamp
            $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

            // Audit logging
            $this->auditService->logCustom('login_oauth', 'auth', $user['id'], ['username' => $user['username'], 'provider' => 'google']);

            return $this->redirectByRole();

        } catch (\Exception $e) {
            log_message('error', 'Google Auth Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->to('/login')->with('error', 'Google authentication error: ' . $e->getMessage());
        }
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

    /**
     * Show department selection form for new Google OAuth users
     */
    public function selectDepartment()
    {
        if (!session()->get('oauth_temp_user_id')) {
            return redirect()->to('/login')->with('error', 'Invalid session. Please try logging in again.');
        }

        $departmentModel = model('DepartmentModel');
        $departments = $departmentModel->findAll();

        return view('auth/select_department', [
            'departments' => $departments,
            'user_email'  => session()->get('oauth_temp_email'),
        ]);
    }

    /**
     * Handle department selection submission
     */
    public function setDepartment()
    {
        if (!session()->get('oauth_temp_user_id')) {
            return redirect()->to('/login')->with('error', 'Invalid session. Please try logging in again.');
        }

        $departmentId = $this->request->getPost('department_id');

        if (empty($departmentId)) {
            return redirect()->back()->with('error', 'Please select a department.');
        }

        $userId = session()->get('oauth_temp_user_id');

        // Update user with selected department
        $this->userModel->update($userId, ['department_id' => $departmentId]);
        $user = $this->userModel->find($userId);

        // Clear temp session data and set login session
        session()->remove(['oauth_temp_user_id', 'oauth_temp_username', 'oauth_temp_email', 'oauth_temp_role', 'oauth_temp_profile']);
        
        session()->set([
            'user_id'         => $user['id'],
            'username'        => $user['username'],
            'email'           => $user['email'],
            'user_role'       => $user['role'],
            'department_id'   => $user['department_id'],
            'user_status'     => $user['status'],
            'profile_picture' => $user['profile_picture'],
            'isLoggedIn'      => true,
        ]);

        // Update last login timestamp
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Audit logging
        $this->auditService->logCustom('login_oauth', 'auth', $user['id'], ['username' => $user['username'], 'provider' => 'google', 'department_selected' => $departmentId]);

        return $this->redirectByRole();
    }
}

