<?php

namespace App\Controllers;

use App\Library\Result;
use App\Library\ResultCode;
use App\Service\LedgerService;

class LedgerController extends ControllerBase
{
    public function ledgerListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;
        $where = [];
        if (empty($get['date'])) {
            Result::error(ResultCode::PARAMS_INVALID, '请选择一个时间');
        } else {
            $where['query_date'] = $get['date'];
        }

        $ledger = new LedgerService();
        $result = $ledger->ledgerList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        if ($result['status']) {
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }
}
