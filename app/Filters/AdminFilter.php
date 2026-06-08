<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Admin Filter
 * Blocks access for non-admin/warehouse users.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('user_role');

        if (!in_array($role, ['admin', 'warehouse'])) {
            if ($role === 'staff') {
                return redirect()->to('/staff/dashboard')->with('error', 'You do not have permission to access that page.');
            }
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}
