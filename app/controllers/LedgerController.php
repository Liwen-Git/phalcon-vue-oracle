<?php

namespace App\Controllers;

use App\Library\Common;
use App\Library\Result;
use App\Library\ResultCode;
use App\Service\LedgerService;
use App\Service\SubjectService;

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
     * 序时账 科目 下拉select列表
     */
    public function subjectSelectAction()
    {
        $subject = new SubjectService();
        $result = $subject->getSubjectSelect([], 1, 1000);
        $arr = Common::listToTree($result['data'], 0, 'subject_code', 'parent_subject', 'child');

        Result::success($arr);
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

    /**
     * 序时账
     */
    public function journalLedgerListAction()
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
            $len = count($get['subject_code']);
            $where['subject_code'] = $get['subject_code'][$len - 1];
        }
        if (!empty($get['chn_name'])) {
            $where['chn_name'] = $get['chn_name'];
        }
        if ($get['group_related'] || $get['group_related'] === '0') {
            $where['group_related'] = $get['group_related'];
        }
        if (!empty($get['merchant_id'])) {
            $where['merchant_id'] = $get['merchant_id'];
        }
        if (!empty($get['agent_id'])) {
            $where['agent_id'] = $get['agent_id'];
        }

        $ledger = new LedgerService();
        $result = $ledger->getJournalLedgerList($where, $page, $pageSize);

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
     * 试算平衡
     */
    public function trialBalanceListAction()
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
        if (!empty($get['subject_level'])) {
            $where['subject_level'] = $get['subject_level'];
        }

        $ledger = new LedgerService();
        $result = $ledger->getTrialBalanceList($where, $page, $pageSize);

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
