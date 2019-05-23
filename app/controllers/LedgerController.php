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

    /**
     * 明细账
     */
    public function subsidiaryLedgerListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;
        $where = [];
        if (!empty($get['query_start_date'])) {
            $where['query_start_date'] = $get['query_start_date'];
        }
        if (!empty($get['query_end_date'])) {
            $where['query_end_date'] = $get['query_end_date'];
        }
        if (!empty($get['subject_code'])) {
            $where['subject_code'] = $get['subject_code'];
        }
        if (!empty($get['subject_level'])) {
            $where['subject_level'] = $get['subject_level'];
        }
        $ledger = new LedgerService();
        $result = $ledger->getSubsidiaryLedgerList($where, $page, $pageSize);

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
