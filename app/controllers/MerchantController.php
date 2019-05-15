<?php

namespace App\Controllers;

use App\Library\Result;
use App\Library\ResultCode;
use App\Service\MerchantService;

class MerchantController extends ControllerBase
{
    /**
     * 通过商户名 查询商户信息
     */
    public function query_merchant_infoAction()
    {
        $post = $this->request->getJsonRawBody(true);

        $where = [];
        if (!empty($post['merchant_name'])) {
            $where['merchant_name'] = $post['merchant_name'];
        }
        $merchant = new MerchantService();
        $data = $merchant->merchantList($where, 1, 20);

        $list = [];
        $total = 0;
        if ($data['status']){
            $list = $data['data']['list'];
            $total = $data['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    /**
     * 列表
     */
    public function listAction()
    {
        $get = $this->request->get();

        $page = $get['page'] ?: 1;
        $page_size = $get['page_size'] ?: 10;

        $where = [];
        if (!empty($get['merchant_id'])) {
            $where['merchant_id'] = $get['merchant_id'];
        }
        if (!empty($get['agent_id'])) {
            $where['agent_id'] = $get['agent_id'];
        }
        if (!empty($get['merchant_name'])) {
            $where['merchant_name'] = $get['merchant_name'];
        }

        $merchant = new MerchantService();
        $data = $merchant->merchantList($where, $page, $page_size);

        $list = [];
        $total = 0;
        if ($data['status']){
            $list = $data['data']['list'];
            $total = $data['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    public function detailAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;
        $where = [];
        if (!empty($get['merchant_id'])) {
            $where['merchant_id'] = $get['merchant_id'];
        } else {
            Result::error(ResultCode::UNKNOWN, '请选择一位商户');
        }

        $merchant = new MerchantService();
        $data = $merchant->merchantDetailList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        if ($data['status']){
            $list = $data['data']['list'];
            $total = $data['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }
}
