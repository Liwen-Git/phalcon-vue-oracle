<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BusinessTypeService;
use App\Service\OperateLogService;
use App\Service\ProfitService;

class ProfitController extends ControllerBase
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
     * 分润明细
     */
    public function agentProfitDetailListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $profit = new ProfitService();
        $result = $profit->getAgentProfitDetailList($where, $page, $pageSize);

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
     * 分润明细导出
     */
    public function exportAgentProfitDetailAction()
    {
        $get = $this->request->get();

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $where['oper_type'] = '1';
        $profit = new ProfitService();
        $result = $profit->exportAgentProfitDetailList($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSDETAILEXPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSDETAILEXPORT, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }

    /**
     * 未分润明细 列表
     */
    public function profitFailDetailListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $profit = new ProfitService();
        $result = $profit->getProfitFailDetailList($where, $page, $pageSize);

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
     * 未分润明细 导出
     */
    public function exportProfitFailDetailAction()
    {
        $get = $this->request->get();

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $where['oper_type'] = '1';
        $profit = new ProfitService();
        $result = $profit->exportProfitFailDetailList($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSFAILEXPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::AGENTPSFAILEXPORT, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }

    /**
     * 利润报表
     */
    public function profitListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if ($k == 'busitypes_code') {
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
            } elseif ($k == 'direct_sign'){
                //是否直签
                if ($v || $v === '0'){
                    $where[$k] = $v;
                }
            } else {
                if ($v && !empty($v)){
                    $where[$k] = $v;
                }
            }
        }

        $profit = new ProfitService();
        $result = $profit->getProfitList($where, $page, $pageSize);

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
     * 利润报表明细下载
     */
    public function downProfitDetailAction()
    {
        $get = $this->request->get();

        $where = [];
        $where['profit_sum_id'] = $get['profit_sum_id'];

        $where['oper_type'] = '1';
        $profit = new ProfitService();
        $result = $profit->downProfitDetail($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITDETAILDOWN, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITDETAILDOWN, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }

    /**
     * 利润报表导出
     */
    public function exportProfitListAction()
    {
        $get = $this->request->get();

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if ($k == 'busitypes_code') {
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
            } elseif ($k == 'direct_sign'){
                //是否直签
                if ($v || $v === '0'){
                    $where[$k] = $v;
                }
            } else {
                if ($v && !empty($v)){
                    $where[$k] = $v;
                }
            }
        }

        $where['oper_type'] = '1';
        $profit = new ProfitService();
        $result = $profit->exportProfitList($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITLISTEXPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITLISTEXPORT, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }

    /**
     * 利润明细
     */
    public function profitDetailListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $profit = new ProfitService();
        $result = $profit->getProfitDetailList($where, $page, $pageSize);

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
     * 利润明细导出
     */
    public function exportProfitDetailAction()
    {
        $get = $this->request->get();

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $where['oper_type'] = '1';
        $profit = new ProfitService();
        $result = $profit->exportProfitDetail($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITDETAILEXPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITDETAILEXPORT, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }

    /**
     * 获取重跑列表
     */
    public function modifyReportListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }

            if ($v && !empty($v)){
                $where[$k] = $v;
            }
        }

        $profit = new ProfitService();
        $result = $profit->getModifyReportList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        if ($result['status']) {
            $list = $result['data']['list']['list'];
            $total = $result['data']['list']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    /**
     * 重跑分润
     */
    public function modifyReportAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $param = [];
        $param['trade_begin_time'] = $post['trade_begin_time'];
        $param['trade_end_time'] = $post['trade_end_time'];
        if(!empty($post['busitypes_code'])){
            //业务类型
            if(!empty($post['busitypes_code'][0])){
                $param['busi_type'] = $post['busitypes_code'][0];
            }
            if(!empty($post['busitypes_code'][1])){
                $param['second_busi_type'] = $post['busitypes_code'][1];
            }
            if(!empty($post['busitypes_code'][2])){
                $param['sub_busi_type'] = $post['busitypes_code'][2];
            }
        }
        if(!empty($post['merchant_id'])) $param['merchant_id'] = $post['merchant_id'];
        if(!empty($post['agent_id'])) $param['agent_id'] = $post['agent_id'];
        if(!empty($post['chl_name'])) $param['chl_name'] = $post['chl_name'];
        if(!empty($post['oder_id'])) $param['oder_id'] = $post['oder_id'];
        if(($post['order_type'] !== '')) $param['order_type'] = $post['order_type'];
        if($post['type'] !== '') $param['type'] = $post['type'];
        if(!empty($post['sum_id'])) $param['sum_id'] = $post['sum_id'];

        $profit = new ProfitService();
        $result = $profit->modifyReport($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::MODIFIEDREPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '重跑分润失败');
        }

        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::MODIFIEDREPORT, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 日利润报表
     */
    public function dailyProfitListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $profit = new ProfitService();
        $result = $profit->getDailyProfitList($where, $page, $pageSize);

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
     * 日利润报表导出
     */
    public function exportDailyProfitAction()
    {
        $get = $this->request->get();

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
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

        $where['oper_type'] = '1';
        $profit = new ProfitService();
        $result = $profit->exportDailyProfit($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITDAILYEXPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::PROFITDAILYEXPORT, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }
}
