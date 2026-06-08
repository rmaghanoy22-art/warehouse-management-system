<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;

class DepartmentsApi extends BaseController
{
    public function index()
    {
        return $this->response->setJSON((new DepartmentModel())->findAll());
    }
}
