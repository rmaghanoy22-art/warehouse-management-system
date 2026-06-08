<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SupplyRequestModel;

class SupplyRequestsApi extends BaseController
{
    public function stats()
    {
        return $this->response->setJSON((new SupplyRequestModel())->getStatusCounts());
    }
}
