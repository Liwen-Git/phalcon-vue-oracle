<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BusinessTypeService;
use App\Service\OperateLogService;
use App\Service\ReportOfAgentService;

class ReportOfAgentController extends ControllerBase
{
    /**
     * 业务类型
     */
    public function businessTypesOfNewAction()
    {
        $type = new BusinessTypeService();
        $result = $type->getBusinessTypesOfNew();

        Result::success($result['data']);
    }

    /**
     * 代理商分润报表 列表
     */
    public function agentProfitSharingListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'state'){
                //分润状态
                if(!empty($v)){
                    $where[$k] = implode(',', $v);
                }
            }elseif($k == 'profit_share_cycle'){
                //代理商分润周期
                if ($v || $v === '0'){
                    $where[$k] = $v;
                }
            }elseif($k == 'busitypes_code'){
                //业务类型
                if(!empty($v[0])){
                    $where['busi_type'] = $v[0];
                }
                if(!empty($v[1])){
                    $where['second_busi_type'] = $v[1];
                }
                if(!empty($v[2])){
                    $where['sub_busi_type'] = $v[2];
                }
            }else{
                if ($v && !empty($v)){
                    $where[$k] = $v;
                }
            }
        }

        $report = new ReportOfAgentService();
        $result = $report->getAgentProfitSharingList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        $sum = [];
        if ($result['status']) {
            $list = $result['data']['list']['list'];
            $total = $result['data']['list']['total'];
            unset($result['data']['list']['list']);
            unset($result['data']['list']['total']);
            $sum = $result['data']['list'];
        }
        Result::success([
            'list' => $list,
            'total' => $total,
            'sum' => $sum,
        ]);
    }

    /**
     * 编辑
     */
    public function editAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $param = [];
        if (!empty($post['other_amt'])) {
            $param['other_amt'] = round($post['other_amt'] * 100);
        }
        $param['agentps_sum_id'] = $post['agentps_sum_id'];
        $param['memo'] = $post['memo'];

        $report = new ReportOfAgentService();
        $result = $report->editAgentProfitSharing($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSUPDATE, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '代理商分润编辑失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSUPDATE, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 代理商分润审核
     */
    public function checkAction()
    {
        $param = $this->request->getJsonRawBody(true);
        $param['check_oper_name'] = $this->user['name'];

        $report = new ReportOfAgentService();
        $result = $report->checkAgentProfitSharing($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSCHECK, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '代理商分润审核失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSCHECK, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 代理商分润报表明细下载
     */
    public function downDetailAction()
    {
        $get = $this->request->get();
        $where = [];
        $where['agentps_sum_id'] = $get['agentps_sum_id'];
        $where['oper_type'] = '1';

        $report = new ReportOfAgentService();
        $result = $report->downAgentProfitSharingDetail($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSDETAILDOWN, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '代理商分润报表明细下载失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSDETAILDOWN, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }

    public function auditAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $auditData = [];

        if ($post['is_all'] == '1') {
            $auditData['changestate'] = $post['changestate'];
            $auditData['check_oper_name'] = $this->user['name'];
            $auditData['memo'] = $post['memo'];
            if (!empty($post['ps_date_start'])) {
                $auditData['ps_date_start'] = $post['ps_date_start'];
            }
            if (!empty($post['ps_date_end'])) {
                $auditData['ps_date_end'] = $post['ps_date_end'];
            }
            if (!empty($post['agentps_sum_id'])) {
                $auditData['agentps_sum_id'] = $post['agentps_sum_id'];
            }
            if (!empty($post['agent_id'])) {
                $auditData['agent_id'] = $post['agent_id'];
            }
            if (!empty($post['agent_name'])) {
                $auditData['agent_name'] = $post['agent_name'];
            }
            if (!empty($post['merchant_id'])) {
                $auditData['merchant_id'] = $post['merchant_id'];
            }
            if (!empty($post['merchant_name'])) {
                $auditData['merchant_name'] = $post['merchant_name'];
            }
            if (!empty($post['profit_share_cycle']) || $post['profit_share_cycle'] === '0'){
                $auditData['profit_share_cycle'] = $post['profit_share_cycle'];
            }
            if (!empty($post['state'])) {
                $auditData['state'] = implode(',', $post['state']);
            }
            if (!empty($post['busitypes_code'])) {
                if(!empty($post['busitypes_code'][0])){
                    $auditData['busi_type'] = $post['busitypes_code'][0];
                }
                if(!empty($post['busitypes_code'][1])){
                    $auditData['second_busi_type'] = $post['busitypes_code'][1];
                }
                if(!empty($post['busitypes_code'][2])){
                    $auditData['sub_busi_type'] = $post['busitypes_code'][2];
                }
            }
        } else {
            $auditData['agentps_sum_ids'] = $post['agentps_sum_ids'];
            $auditData['changestate'] = $post['changestate'];
            $auditData['check_oper_name'] = $this->user['name'];
            $auditData['memo'] = $post['memo'];
        }

        $report = new ReportOfAgentService();
        $result = $report->checkAgentProfitSharing($auditData);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSAUDIT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '代理商分润审核失败');
        }

        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSAUDIT, OperateLog::STATUS_SUCCESS);

        Result::success();
    }
}
