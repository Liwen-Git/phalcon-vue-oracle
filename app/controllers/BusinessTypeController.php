<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BusinessTypeService;
use App\Service\OperateLogService;

class BusinessTypeController extends ControllerBase
{
    /**
     * 获取所有的业务类型(新)
     */
    public function businessTypesOfNewAction()
    {
        $type = new BusinessTypeService();
        $result = $type->getBusinessTypesOfNew(1);

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
        if (!empty($get['business_types'])) {
            if (!empty($get['business_types'][0])) {
                $where['busi_type'] = $get['business_types'][0];
            }
            if (!empty($get['business_types'][1])) {
                $where['second_busi_type'] = $get['business_types'][1];
            }
            if (!empty($get['business_types'][2])) {
                $where['sub_busi_type'] = $get['business_types'][2];
            }
        }
        if (!empty($get['state'])) {
            $where['state'] = $get['state'];
        }

        $business = new BusinessTypeService();
        $result = $business->getList($where, $page, $pageSize);

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
     * 添加
     */
    public function addAction()
    {
        $data = $this->request->getJsonRawBody(true);
        if (empty($data['busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务大类代码不允许为空');
        }
        if (empty($data['busy_type_name'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务大类名称不允许为空');
        }
        if (empty($data['second_busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务小类代码不允许为空');
        }
        if (empty($data['second_busi_name'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务小类名称不允许为空');
        }
        if (empty($data['sub_busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务子类代码不允许为空');
        }
        if (empty($data['sub_busi_name'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务子类名称不允许为空');
        }
        if (empty($data['remark'])) {
            Result::error(ResultCode::PARAMS_INVALID, '备注不允许为空');
        }
        $data['state'] = '1';
        $data['oper_name'] = $this->user['name'];

        $business = new BusinessTypeService();
        $result = $business->addAndEditBusinessType($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BUSITYPEADD, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '新增业务类型失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BUSITYPEADD, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 编辑
     */
    public function editAction()
    {
        $data = $this->request->getJsonRawBody(true);
        if (empty($data['busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务大类代码不允许为空');
        }
        if (empty($data['busy_type_name'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务大类名称不允许为空');
        }
        if (empty($data['second_busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务小类代码不允许为空');
        }
        if (empty($data['second_busi_name'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务小类名称不允许为空');
        }
        if (empty($data['sub_busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务子类代码不允许为空');
        }
        if (empty($data['sub_busi_name'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务子类名称不允许为空');
        }
        if (empty($data['state'])) {
            Result::error(ResultCode::PARAMS_INVALID, '状态不允许为空');
        }
        if (empty($data['remark'])) {
            Result::error(ResultCode::PARAMS_INVALID, '备注不允许为空');
        }
        $data['oper_name'] = $this->user['name'];

        $business = new BusinessTypeService();
        $result = $business->addAndEditBusinessType($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BUSITYPEEDIT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '编辑业务类型失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::BUSITYPEEDIT, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 获取所有业务大类
     */
    public function getAllBizTypesAction()
    {
        $business = new BusinessTypeService();
        $res = $business->getAllBizTypes();
        if (!$res['status']) {
            Result::success([]);
        }
        Result::success($res['data']);
    }
}
