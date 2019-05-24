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
}
