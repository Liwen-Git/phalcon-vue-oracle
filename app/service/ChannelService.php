<?php

namespace App\Service;

class ChannelService extends BaseService
{

    /**
     * 渠道列表
     * @param array $param
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getList(array $param, $page, $pageSize)
    {
        $param['interface_type'] = 'querychllist';
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('数据获取失败[渠道列表]');
        }
        return $this->makeBack('获取成功[渠道列表]', true, $result['data']);
    }

    /**
     * 添加渠道
     * @param $param
     * @return array
     */
    public function addChannel($param)
    {
        $param['interface_type'] = 'addchannelfeerate';
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('添加渠道失败');
        }
        return $this->makeBack('添加渠道成功', true, $result['data']);
    }
}
