<?php

namespace App\Service;

class ChannelService extends BaseService
{

    public function getList(array $param, $page, $pageSize)
    {
        $param['interface_type'] = 'querychllist';
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('数据获取失败');
        }
        return $this->makeBack('获取成功', true, $result['data']);
    }
}
