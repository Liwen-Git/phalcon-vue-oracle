<?php

namespace App\Controllers;

use App\Library\Common;
use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BalanceService;
use App\Service\OperateLogService;
use App\Service\SubjectService;

class BalanceController extends ControllerBase
{
    /**
     * 列表
     */
    public function listAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['page_size'] ?: 10;

        $where = [];
        if ($get['merchant_type'] == '1') {
            $where['acc_type'] = $get['merchant_type'];
            $where['merchant_id'] = $get['merchant_id'];
            $where['merchant_name'] = $get['merchant_name'];
        } else {
            $where['acc_type'] = $get['merchant_type'];
            $where['merchant_id'] = $get['agent_id'];
            $where['merchant_name'] = $get['agent_name'];
        }

        $balance = new BalanceService();
        $result = $balance->getList($where, $page, $pageSize);

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
     * 余额冻结
     */
    public function frozenAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $param = [];
        if (empty($post['merchant_id'])) {
            Result::error(ResultCode::PARAMS_INVALID, '商户号不允许为空');
        } else {
            $param['merchant_id'] = $post['merchant_id'];
        }
        if (empty($post['acc_amount'])) {
            Result::error(ResultCode::PARAMS_INVALID, '冻结金额不允许为空');
        } else {
            $param['acc_amount'] = $post['acc_amount'] * 100;
        }
        $param['remark'] = $post['remark'];
        $param['oper_type'] = '1';
        $param['oper_name'] = $this->user['name'];

        $balance = new BalanceService();
        $result = $balance->balanceFrozen($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BALANCEFROZEN, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '金额冻结失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BALANCEFROZEN, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 余额解冻
     */
    public function unfrozenAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $param = [];
        if (empty($post['merchant_id'])) {
            Result::error(ResultCode::PARAMS_INVALID, '商户号不允许为空');
        } else {
            $param['merchant_id'] = $post['merchant_id'];
        }
        if (empty($post['acc_amount'])) {
            Result::error(ResultCode::PARAMS_INVALID, '解冻金额不允许为空');
        } else {
            $param['acc_amount'] = $post['acc_amount'] * 100;
        }
        $param['remark'] = $post['remark'];
        $param['oper_type'] = '2';
        $param['oper_name'] = $this->user['name'];

        $balance = new BalanceService();
        $result = $balance->balanceFrozen($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BALANCEUNFROZEN, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '金额解冻失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BALANCEUNFROZEN, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 手工记账 列表
     */
    public function queryManualListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['page_size'] ?: 10;

        $where = [];
        if ($get['acc_inc']) {
            $where['acc_inc'] = $get['acc_inc'];
        }
        if ($get['query_start_date']) {
            $where['query_start_date'] = $get['query_start_date'];
        }
        if ($get['query_end_date']) {
            $where['query_end_date'] = $get['query_end_date'];
        }
        if ($get['second_busi_type']) {
            $where['second_busi_type'] = $get['second_busi_type'];
        }
        if ($get['state']) {
            $where['state'] = $get['state'];
        }

        $balance = new BalanceService();
        $result = $balance->getQueryManualList($where, $page, $pageSize);

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
     * 手工录入时 科目下拉列表
     */
    public function subjectSelectAction()
    {
        $subject = new SubjectService();
        $result = $subject->getSubjectSelect([], 1, 1000);
        $arr = Common::listToTree($result['data'], 0, 'subject_code', 'parent_subject', 'child');

        Result::success($arr);
    }

    /**
     * 查询虚户余额
     */
    public function queryVirtualBalanceAction()
    {
        $get = $this->request->get();
        $balance = new BalanceService();
        $result = $balance->queryVirtualBalance($get);

        $data = [];
        if ($result['status']) {
            $data = $result['data'];
        }
        Result::success(['data' => $data]);
    }

    /**
     * 手工记账录入
     */
    public function addManualAction()
    {
        $post = $this->request->getJsonRawBody(true);
        if ($post['second_busi_type'] == '05') {
            foreach ($post['list'] as &$item) {
                if ($item['acc_type'] == '4') {
                    $len = count($item['subject_codes']);
                    $item['subject_code'] = $item['subject_codes'][$len - 1];
                }
                $item['acc_amount'] = intval($item['acc_amount'] * 100);
            }

            $data = [];
            $data['second_busi_type'] = $post['second_busi_type'];
            $data['oper_name'] = $this->user['name'];
            $data['verify_name'] = $this->user['name'];
            $data['remark'] = $post['remark'];
            $data['data'] = $post['list'];

            $balance = new BalanceService();
            $result = $balance->addManual($data);
        } else {
            $data = [];
            $data['second_busi_type'] = $post['second_busi_type'];
            $data['oper_name'] = $this->user['name'];
            $data['verify_name'] = $this->user['name'];
            $data['remark'] = $post['remark'];
            $data['data'][0]['acc_type'] = $post['acc_type'];
            $data['data'][0]['acc_id'] = $post['acc_id'];
            $data['data'][0]['acc_amount'] = intval($post['acc_amount'] * 100);

            $balance = new BalanceService();
            $result = $balance->addManual($data);
        }

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::MANUALADD, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '手工记账录入失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::MANUALADD, OperateLog::STATUS_SUCCESS);

        Result::success();
    }
}
