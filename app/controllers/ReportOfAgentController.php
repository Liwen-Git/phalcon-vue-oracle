<?php

namespace App\Controllers;

use App\Library\Result;
use App\Service\BusinessTypeService;
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
}
