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
}
