<?php

namespace App\Service;

class AccountingEntryService extends BaseService
{
    /**
     * 会计分录 列表
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = 'queryEntry';
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('数据获取失败[会计分录]');
        }

        return $this->makeBack('获取成功[会计分录]', true, $result['data']);
    }

    /**
     * 会计分录录入/修改
     * @param $data
     * @return array
     */
    public function addAndUpdateAccountingEntry($data)
    {
        $data['interface_type'] = "updateEntry";
        $result = $this->postHttp("ledger",$data);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[会计分录录入/修改]");
        }

        return $this->makeBack("获取成功[会计分录录入/修改]", true, $result['data']);
    }
}
