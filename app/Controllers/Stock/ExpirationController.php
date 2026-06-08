<?php

namespace App\Controllers\Stock;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ExpirationController extends BaseController
{
    public function __construct()
    {
        helper(['permission', 'format']);
    }

    public function index()
    {
        $productModel = new ProductModel();
        $data = [
            'title'            => 'Expiration Tracking',
            'expiringProducts' => $productModel->getExpiringProducts(365),
        ];
        return view('stock/expiration', $data);
    }
}
