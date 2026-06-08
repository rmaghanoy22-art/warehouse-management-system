<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class ProductsApi extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        return $this->response->setJSON($model->getProductsWithCategory($search, $category));
    }

    public function show(int $id)
    {
        $product = (new ProductModel())->find($id);
        if (!$product) return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
        return $this->response->setJSON($product);
    }
}
