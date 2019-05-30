<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BankService;
use App\Service\BusinessTypeService;
use App\Service\ChannelService;
use App\Service\OperateLogService;

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

    /**
     * 列表
     */
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

    /**
     * 银行名称 搜索
     */
    public function queryBankInfoAction()
    {
        $get = $this->request->get();
        $where = [];
        if (!empty($get['bank_name'])) {
            $where['bank_name'] = $get['bank_name'];
        }
        $bank = new BankService();
        $result = $bank->bankList($where, 1, 100);

        $list = [];
        $total = 0;
        if ($result['status']){
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    /**
     * 添加渠道
     */
    public function addAction()
    {
        $post = $this->request->getJsonRawBody(true);
        if (empty($post['business_types'])) {
            Result::error(ResultCode::UNKNOWN, '业务大类不允许为空');
        }
        $data = [
            'chl_name'              => $post['chl_name'],
            'sys_chl_code'          => $post['sys_chl_code'],
            "channel_merchant_id"   => $post['channel_merchant_id'],
            "bank_code"             => $post['bank_code'],
            'busi_type'             => $post['business_types'][0],
            'second_busi_type'      => !empty($post['business_types'][1]) ? $post['business_types'][1] : '',
            'oper_name'             => $this->user['name'],
            "bank_card_type"        => $post['bank_card_type'],
            'charge_method'         => $post['fee_settle_type'],
            'charge_type'           => $post['charge_type'],
            'memo'                  => $post['memo'],
            'service_start_time'    => $post['service_start_time'],
            'service_end_time'      => $post['service_end_time'],
            'list'                  => $post['list'],
        ];

        $channel = new ChannelService();
        $result = $channel->addChannel($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELADD, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '渠道费率增加失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELADD, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 编辑
     */
    public function editAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $data = [
            'fee_rate_seq_no'       => $post['fee_rate_seq_no'],
            'channel_merchant_id'   => $post['channel_merchant_id'],
            'bank_card_type'        => $post['bank_card_type'],
            'charge_method'         => $post['fee_settle_type'],
            'charge_type'           => $post['charge_type'],
            'service_start_time'    => $post['service_start_time'],
            'service_end_time'      => $post['service_end_time'],
            'valid'                 => ($post['state'] == 0 || $post['state'] == 1) ? $post['state'] : '',
            'fee_rate_type'         => $post['fee_rate_type'],
            'left_value_amt'        => $post['left_value_amt'],
            'right_value_amt'       => $post['right_value_amt'],
            'fee_rate'              => $post['fee_rate'],
            'fee_fixed'             => $post['fee_fixed'],
            'min_fee_amt'           => $post['min_fee_amt'],
            'max_fee_amt'           => $post['max_fee_amt'],
            'oper_name'             => $this->user['name'],
        ];

        $channel = new ChannelService();
        $result = $channel->updateChannel($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELEDIT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '渠道费率编辑失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELEDIT, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $data = [
            'fee_rate_seq_no'       => $post['fee_rate_seq_no'],
            'valid'                 => '0',
            'oper_name'             => $this->user['name'],
        ];

        $channel = new ChannelService();
        $result = $channel->updateChannel($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELDEL, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_DELETE_FAIL, '渠道费率删除失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELDEL, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 搜索渠道信息
     */
    public function queryChannelInfoAction()
    {
        $get = $this->request->get();
        $where = [];
        if (!empty($get['acc_name'])) {
            $where['acc_name'] = $get['acc_name'];
        }

        $channel = new ChannelService();
        $result = $channel->queryChannelInfo($where, 1, 100);

        $list = [];
        $total = 0;
        if ($result['status']){
            $list = $result['data']['list'];
            $total = $result['data']['total'];
        }

        Result::success([
            'list' => $list,
            'total' => $total,
        ]);
    }

    /**
     * 渠道交易报表
     */
    public function channelTradeListAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
                //业务类型
                if(!empty($v[0])){
                    $where['busi_type'] = $v[0];
                }
                if(!empty($v[1])){
                    $where['second_busi_type'] = $v[1];
                }
                if(!empty($v[2])){
                    $where['sub_busi_type'] = $v[2];
                }
            }else{
                if ($v && !empty($v)){
                    $where[$k] = $v;
                }
            }
        }

        $channel = new ChannelService();
        $result = $channel->getChannelTradeList($where, $page, $pageSize);

        $list = [];
        $total = 0;
        $sum = [];
        if ($result['status']) {
            $list = $result['data']['list']['list'];
            $total = $result['data']['list']['total'];
            unset($result['data']['list']['list']);
            unset($result['data']['list']['total']);
            $sum = $result['data']['list'];
        }
        Result::success([
            'list' => $list,
            'total' => $total,
            'sum' => $sum,
        ]);
    }

    /**
     * 渠道交易报表导出
     */
    public function exportChannelTradeAction()
    {
        $get = $this->request->get();

        $where = [];
        foreach ($get as $k => $v){
            if ($k == 'page' || $k == 'pageSize'){
                continue;
            }
            if($k == 'busitypes_code'){
                //业务类型
                if(!empty($v[0])){
                    $where['busi_type'] = $v[0];
                }
                if(!empty($v[1])){
                    $where['second_busi_type'] = $v[1];
                }
                if(!empty($v[2])){
                    $where['sub_busi_type'] = $v[2];
                }
            }else{
                if ($v && !empty($v)){
                    $where[$k] = $v;
                }
            }
        }

        $where['oper_type'] = '1';

        $channel = new ChannelService();
        $result = $channel->exportChannelTrade($where);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELTRADEEXPORT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_QUERY_FAIL, '导出失败');
        }

        $list = $result['data']['list'];
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::CHANNELTRADEEXPORT, OperateLog::STATUS_SUCCESS);

        Result::success(['list' => $list]);
    }
}
