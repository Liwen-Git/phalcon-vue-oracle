<?php

namespace App\Controllers;

use App\Library\Result;
use App\Library\ResultCode;
use App\Service\AgentService;

class AgentController extends ControllerBase
{
    public function listAction()
    {
        $get = $this->request->get();

        $page = $get['page'] ?: 1;
        $page_size = $get['page_size'] ?: 10;

        $where = [];
        if (!empty($get['agent_id'])) {
            $where['agent_id'] = $get['agent_id'];
        }
        if (!empty($get['agent_name'])) {
            $where['agent_name'] = $get['agent_name'];
        }
        $agent = new AgentService();
        $result = $agent->agentList($where, $page, $page_size);

        $list = [];
        $total = 0;
        if ($result['status']){
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    public function query_agent_infoAction()
    {
        $get = $this->request->get();

        $where = [];
        if (!empty($get['agent_name'])) {
            $where['agent_name'] = $get['agent_name'];
        }
        $agent = new AgentService();
        $result = $agent->agentList($where, 1, 20);

        $list = [];
        $total = 0;
        if ($result['status']){
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    public function detailAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;
        $where = [];
        if (!empty($get['agent_id'])) {
            $where['agent_id'] = $get['agent_id'];
        } else {
            Result::error(ResultCode::UNKNOWN, '请选择一位代理商');
        }

        $agent = new AgentService();
        $result = $agent->agentDetailList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        if ($result['status']){
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }
}
