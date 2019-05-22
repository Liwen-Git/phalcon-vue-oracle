<?php

namespace App\Service;

class LedgerService extends BaseService
{

    /**
     * 总账
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function ledgerList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryGeneralLedger";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[总账]");
        }

        return $this->makeBack("获取成功[总账]", true, $result['data']);
    }
}
