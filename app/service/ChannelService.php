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
    public function getList(array $param, $page = 1, $pageSize = 10)
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

    /**
     * 更新渠道费率
     * @param array $param
     * @return array
     */
    public function updateChannel(array $param)
    {
        $param['interface_type'] = "updatechannelfeerate";
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack("更新渠道费率失败");
        }

        return $this->makeBack("更新渠道费率成功", true, $result['data']);
    }

    /**
     * 搜索渠道信息
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function queryChannelInfo(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "queryChlInfo";
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp("ledger", $param);
        if (!$result['status']){
            return $this->makeBack("数据获取失败[搜索渠道信息]");
        }

        return $this->makeBack("获取成功[搜索渠道信息]", true, $result['data']);
    }
}
