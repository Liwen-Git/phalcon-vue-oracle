<?php

namespace App\Service;

class BalanceService extends BaseService
{
    /**
     * 余额查询列表
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryBalance";
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[余额查询列表]");
        }

        return $this->makeBack("获取成功[余额查询列表]", true, $result['data']);
    }

    /**
     * [余额冻结/解冻]
     * @param array $param
     * @return array
     */
    public function balanceFrozen(array $param)
    {
        $param['interface_type'] = "operBalance";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[余额冻结/解冻]");
        }

        return $this->makeBack("获取成功[余额冻结/解冻]", true, $result['data']);
    }

    /**
     * 手工记账查询
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getQueryManualList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryManual";
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[手工记账查询]");
        }

        return $this->makeBack("获取成功[手工记账查询]", true, $result['data']);
    }

    /**
     * 查询虚户余额
     * @param $param
     * @return array
     */
    public function queryVirtualBalance($param)
    {
        $param['interface_type'] = "queryVirtualBal";
        $res = $this->postHttp("ledger", $param, '1');
        if (!$res['status']){
            return $this->makeBack("数据获取失败[查询虚户余额]");
        }

        return $this->makeBack("获取成功[查询虚户余额]", true, $res['data']);
    }

    /**
     * 手工记账录入
     * @param array $param
     * @return array
     */
    public function addManual(array $param)
    {
        $param['interface_type'] = "updateManual";
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[手工记账录入]");
        }

        return $this->makeBack("获取成功[手工记账录入]", true, $result['data']);
    }
}
