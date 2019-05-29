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
    public function getAgentProfitDetailList($param, $page = 1, $pageSize = 10)
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
    public function exportAgentProfitDetailList(array $param)
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

    /**
     * 利润报表
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getProfitList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "qryprofitlist";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[利润报表]");
        }
        return $this->makeBack("数据获取成功[利润报表]", true, $result['data']);
    }

    /**
     * 利润报表明细下载
     * @param array $param
     * @return array
     */
    public function downProfitDetail(array $param)
    {
        $param['interface_type'] = "qryprofitdetail";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[利润报表明细下载]");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据[利润报表明细下载]");
        }

        return $this->makeBack("获取成功[利润报表明细下载]", true, $result['data']);
    }

    /**
     * 利润报表导出
     * @param array $param
     * @return array
     */
    public function exportProfitList(array $param)
    {
        $param['interface_type'] = "qryprofitlist";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[利润报表导出]");
        }
        return $this->makeBack("数据获取成功[利润报表导出]", true, $result['data']);
    }

    /**
     * 利润明细
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getProfitDetailList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "qryprofitdetail";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[利润明细]");
        }
        return $this->makeBack("数据获取成功[利润明细]", true, $result['data']);
    }

    /**
     * 利润明细导出
     * @param array $param
     * @return array
     */
    public function exportProfitDetail(array $param)
    {
        $param['interface_type'] = "qryprofitdetail";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[利润明细导出]");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据[利润明细导出]");
        }

        return $this->makeBack("获取成功[利润明细导出]", true, $result['data']);
    }
}
