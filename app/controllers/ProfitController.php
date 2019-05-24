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
     * 分润明细导出
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
        $result = $profit->exportProfitDetailList($where);

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
}
