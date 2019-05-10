<?php

namespace App\Controllers;

use App\Library\Result;
use App\Service\OperateLogService;

class OperateController extends ControllerBase
{
    /**
     * 日志列表
     */
    public function listAction()
    {
        $param = $this->request->get();
        $userId = $param['userId'];
        $action = $param['action'];
        $status = $param['status'];
        $startDate = $param['startDate'];
        $endDate = $param['endDate'];
        $page = $param['page'];
        $pageSize = $param['pageSize'];

        $params = compact('userId', 'action', 'status', 'startDate', 'endDate', 'page', 'pageSize');
        $log = new OperateLogService();
        $data = $log->getList($params);

        Result::success([
            'list' => $data->items,
            'total' => $data->total_items,
        ]);
    }
}
