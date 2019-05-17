<?php

namespace App\Service;

class BankService extends BaseService
{
    /**
     * 银行列表
     * @param $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function bankList($param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = 'queryBankInfoList';
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);

        if (!$result['status']){
            return $this->makeBack("银行列表数据获取失败");
        }

        return $this->makeBack("银行列表获取成功", true, $result['data']['list']);
    }
}
