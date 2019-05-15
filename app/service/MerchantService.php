<?php

namespace App\Service;

class MerchantService extends BaseService
{
    /**
     * @param $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function merchantList($param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryMerInfo";
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('数据获取失败');
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据");
        }

        return $this->makeBack("获取成功",true, $result['data']);
    }

    public function merchantDetailList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryMerDetail";
        $param['page_index'] = intval($page);
        $param['page_num'] = intval($pageSize);
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败");
        }
        if ($result['data']['total'] < 1 ){
            return $this->makeBack("无数据");
        }

        return $this->makeBack("获取成功",true, $result['data']);
    }
}
