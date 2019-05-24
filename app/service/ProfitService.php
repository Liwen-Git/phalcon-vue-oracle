<?php

namespace App\Service;

class ProfitService extends BaseService
{
    /**
     * 分润明细
     * @param $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getProfitDetailList($param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "qryagentpsdetail";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[分润明细]");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据[分润明细]");
        }

        return $this->makeBack("获取成功[分润明细]", true, $result['data']);
    }

    /**
     * 分润明细导出
     * @param array $param
     * @return array
     */
    public function exportProfitDetailList(array $param)
    {
        $param['interface_type'] = "qryagentpsdetail";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[分润明细导出]");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据[分润明细导出]");
        }

        return $this->makeBack("获取成功[分润明细导出]", true, $result['data']);
    }

    /**
     * 未分润明细
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getProfitFailDetailList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "qryagentpsfaillist";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[未分润明细]");
        }

        return $this->makeBack("获取成功[未分润明细]", true, $result['data']);
    }

    /**
     * 未分润明细报表导出
     * @param array $param
     * @return array
     */
    public function exportProfitFailDetailList(array $param)
    {
        $param['interface_type'] = "qryagentpsfaillist";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[未分润明细报表导出]");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据[未分润明细报表导出]");
        }

        return $this->makeBack("获取成功[未分润明细报表导出]", true, $result['data']);
    }
}
