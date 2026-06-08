<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Warehouse Filter
 * Ensures only warehouse and admin roles can access stock management routes.
 */
class WarehouseFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('user_role');

        if (!in_array($role, ['admin', 'warehouse'])) {
            return redirect()->to('/staff/dashboard')->with('error', 'Access restricted to warehouse personnel.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}
