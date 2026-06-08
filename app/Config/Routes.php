<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ─── Public Routes ─────────────────────────────────────────
$routes->get('/', 'Auth\AuthController::login');
$routes->get('login', 'Auth\AuthController::login');
$routes->post('login', 'Auth\AuthController::attemptLogin');
$routes->get('register', 'Auth\AuthController::register');
$routes->post('register', 'Auth\AuthController::attemptRegister');
$routes->get('logout', 'Auth\AuthController::logout');

// Google OAuth 2.0 Routes
$routes->get('auth/google/login', 'Auth\GoogleAuthController::login');
$routes->get('auth/google/callback', 'Auth\GoogleAuthController::callback');
$routes->get('auth/google/select-department', 'Auth\GoogleAuthController::selectDepartment');
$routes->post('auth/google/set-department', 'Auth\GoogleAuthController::setDepartment');

// ─── Admin / Warehouse Routes ──────────────────────────────
$routes->group('', ['filter' => ['auth', 'admin']], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard\DashboardController::index');

    // Products
    $routes->get('products', 'Products\ProductController::index');
    $routes->get('products/create', 'Products\ProductController::create');
    $routes->post('products/store', 'Products\ProductController::store');
    $routes->get('products/edit/(:num)', 'Products\ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'Products\ProductController::update/$1');
    $routes->post('products/delete/(:num)', 'Products\ProductController::delete/$1');

    // Product Categories
    $routes->get('categories', 'Products\ProductController::categories');
    $routes->post('categories/store', 'Products\ProductController::storeCategory');
    $routes->post('categories/update/(:num)', 'Products\ProductController::updateCategory/$1');
    $routes->post('categories/delete/(:num)', 'Products\ProductController::deleteCategory/$1');

    // Departments
    $routes->get('departments', 'Departments\DepartmentController::index');
    $routes->get('departments/create', 'Departments\DepartmentController::create');
    $routes->post('departments/store', 'Departments\DepartmentController::store');
    $routes->get('departments/edit/(:num)', 'Departments\DepartmentController::edit/$1');
    $routes->post('departments/update/(:num)', 'Departments\DepartmentController::update/$1');
    $routes->post('departments/delete/(:num)', 'Departments\DepartmentController::delete/$1');

    // Supply Requests (Admin view)
    $routes->get('supply-requests', 'SupplyRequests\SupplyRequestController::index');
    $routes->get('supply-requests/(:num)', 'SupplyRequests\SupplyRequestController::show/$1');
    $routes->post('supply-requests/approve/(:num)', 'SupplyRequests\SupplyRequestController::approve/$1');
    $routes->post('supply-requests/reject/(:num)', 'SupplyRequests\SupplyRequestController::reject/$1');
    $routes->post('supply-requests/release/(:num)', 'SupplyRequests\SupplyRequestController::release/$1');

    // Users
    $routes->get('users', 'Users\UserController::index');
    $routes->get('users/create', 'Users\UserController::create');
    $routes->post('users/store', 'Users\UserController::store');
    $routes->get('users/edit/(:num)', 'Users\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Users\UserController::update/$1');
    $routes->post('users/delete/(:num)', 'Users\UserController::delete/$1');

    // Stock
    $routes->get('stock/logs', 'Stock\StockLogController::index');
    $routes->get('stock/adjust', 'Stock\StockLogController::adjustForm');
    $routes->post('stock/adjust', 'Stock\StockLogController::adjust');
    $routes->get('stock/expiration', 'Stock\ExpirationController::index');

    // Audit Logs
    $routes->get('audit-logs', 'Dashboard\DashboardController::auditLogs');
});

// ─── Staff Routes ──────────────────────────────────────────
$routes->group('staff', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Dashboard\StaffDashboardController::index');
    $routes->get('requests', 'SupplyRequests\StaffRequestController::index');
    $routes->get('requests/create', 'SupplyRequests\StaffRequestController::create');
    $routes->post('requests/store', 'SupplyRequests\StaffRequestController::store');
    $routes->get('requests/(:num)', 'SupplyRequests\StaffRequestController::show/$1');
    $routes->get('inventory', 'Dashboard\StaffDashboardController::inventory');
});

// ─── API Routes ────────────────────────────────────────────
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('products', 'ProductsApi::index');
    $routes->get('products/(:num)', 'ProductsApi::show/$1');
    $routes->get('departments', 'DepartmentsApi::index');
    $routes->get('supply-requests/stats', 'SupplyRequestsApi::stats');
    $routes->get('stock/chart-data', 'StockApi::chartData');
    $routes->get('dashboard/stats', 'DashboardApi::stats');
});
