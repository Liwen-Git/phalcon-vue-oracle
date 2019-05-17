<?php

namespace App\Service;

class AgentService extends BaseService
{
    /**
     * 代理商列表
     * @param $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function agentList($param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryAgentInfo";
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('代理商列表数据获取失败');
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("代理商列表无数据");
        }

        return $this->makeBack("代理商列表获取成功",true, $result['data']);
    }

    /**
     * 代理商 合同费率信息
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function agentDetailList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = 'queryAgentDetail';
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            $this->makeBack('代理商 合同费率信息数据获取失败');
        }
        if ($result['data']['total'] < 1) {
            $this->makeBack('代理商 合同费率信息无数据');
        }

        return $this->makeBack('代理商 合同费率信息获取成功', true, $result['data']);
    }
}
