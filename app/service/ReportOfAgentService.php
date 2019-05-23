<?php

namespace App\Service;

class ReportOfAgentService extends BaseService
{
    /**
     * 代理商分润报表查询
     * @param $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getAgentProfitSharingList($param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "qryagentpslist";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[代理商分润报表查询]");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据[代理商分润报表查询]");
        }

        return $this->makeBack("获取成功[代理商分润报表查询]", true, $result['data']);
    }
}
