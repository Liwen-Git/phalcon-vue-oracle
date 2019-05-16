<?php

namespace App\Controllers;

use App\Library\Result;
use App\Service\BusinessTypeService;
use App\Service\ChannelService;

class ChannelController extends ControllerBase
{
    /**
     * 渠道中 获取 业务类型 获取两级
     */
    public function businessTypesOfNewAction()
    {
        $type = new BusinessTypeService();
        $result = $type->getBusinessTypesOfNew('0', '2');

        Result::success($result['data']);
    }

    public function listAction()
    {
        $get = $this->request->get();

        $page = $get['page'] ?: 1;
        $pageSize = $get['page_size'] ?: 10;
        $where = [];
        if (!empty($get['chl_name'])) {
            $where['chl_name'] = $get['chl_name'];
        }
        if (!empty($get['channel_merchant_id'])) {
            $where['channel_merchant_id'] = $get['channel_merchant_id'];
        }
        if (!empty($get['business_types'])) {
            if (!empty($get['business_types'][0])) {
                $where['busi_type'] = $get['business_types'][0];
            }
            if (!empty($get['business_types'][1])) {
                $where['second_busi_type'] = $get['business_types'][1];
            }
        }
        if (!empty($get['state']) || (isset($get['state']) && $get['state'] === '0')) {
            $where['state'] = $get['state'];
        }

        $channel = new ChannelService();
        $result = $channel->getList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        if ($result['status']) {
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }
        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }
}
