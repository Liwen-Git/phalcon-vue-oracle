<?php

namespace App\Controllers;

use App\Library\Common;
use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\AccountingEntryService;
use App\Service\BusinessTypeService;
use App\Service\OperateLogService;
use App\Service\SubjectService;

class AccountingEntryController extends ControllerBase
{
    /**
     * 业务类型
     */
    public function businessTypesOfNewAction()
    {
        $type = new BusinessTypeService();
        $result = $type->getBusinessTypesOfNew(1);

        Result::success($result['data']);
    }

    /**
     * 科目
     */
    public function subjectSelectAction()
    {
        $subject = new SubjectService();
        $result = $subject->getSubjectSelect([], 1, 1000);
        $arr = Common::listToTree($result['data'], 0, 'subject_code', 'parent_subject', 'child');

        Result::success($arr);
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

        $entry = new AccountingEntryService();
        $result = $entry->getList($where, $page, $pageSize);

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
     * 录入会计分录
     */
    public function addAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $data = [];
        if (empty($post['business_types'][0])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务大类代码不允许为空');
        } else {
            $data['busi_type'] = $post['business_types'][0];
        }
        if (empty($post['business_types'][1])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务小类代码不允许为空');
        } else {
            $data['second_busi_type'] = $post['business_types'][1];
        }
        if (empty($post['business_types'][2])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务子类代码不允许为空');
        } else {
            $data['sub_busi_type'] = $post['business_types'][2];
        }

        foreach ($post['data'] as &$item) {
            if (empty($item['subject_code'])) {
                Result::error(ResultCode::PARAMS_INVALID, '科目不允许为空');
            }
            $item['subject_code'] = $item['subject_code'][count($item['subject_code']) - 1];
        }
        $data['data'] = $post['data'];

        $entry = new AccountingEntryService();
        $result = $entry->addAndUpdateAccountingEntry($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::ENTRYADD, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '会计分录录入失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::ENTRYADD, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 编辑
     */
    public function editAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $data = [];
        if (empty($post['busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务大类代码不允许为空');
        } else {
            $data['busi_type'] = $post['busi_type'];
        }
        if (empty($post['second_busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务小类代码不允许为空');
        } else {
            $data['second_busi_type'] = $post['second_busi_type'];
        }
        if (empty($post['sub_busi_type'])) {
            Result::error(ResultCode::PARAMS_INVALID, '业务子类代码不允许为空');
        } else {
            $data['sub_busi_type'] = $post['sub_busi_type'];
        }
        if (empty($post['subject_code'])) {
            Result::error(ResultCode::PARAMS_INVALID, '科目代码不允许为空');
        }
        $data['data'][] = [
            'subject_code' => $post['subject_code'],
            'depit_credit_dir' => $post['depit_credit_dir'],
            'is_real' => $post['is_real'],
        ];

        $entry = new AccountingEntryService();
        $result = $entry->addAndUpdateAccountingEntry($data);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::ENTRYEDIT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '会计分录编辑失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::ENTRYEDIT, OperateLog::STATUS_SUCCESS);

        Result::success();
    }
}
