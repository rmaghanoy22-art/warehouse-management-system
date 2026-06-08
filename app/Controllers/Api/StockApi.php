<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\StockLogModel;

class StockApi extends BaseController
{
    public function chartData()
    {
        $logs = (new StockLogModel())->select("DATE_FORMAT(created_at, '%Y-%m-%d') as date, log_type, SUM(quantity) as total")
            ->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy("DATE_FORMAT(created_at, '%Y-%m-%d'), log_type")
            ->findAll();
        return $this->response->setJSON($logs);
    }
}
