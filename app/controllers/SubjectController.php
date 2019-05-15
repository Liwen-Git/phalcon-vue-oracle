<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\OperateLogService;
use App\Service\SubjectService;

class SubjectController extends ControllerBase
{
    /**
     * 科目级别
     */
    public function get_subject_levelsAction()
    {
        $levels = SubjectService::subjectLevelArray();

        Result::success($levels);
    }

    /**
     * 科目类型
     */
    public function get_subject_typesAction()
    {
        $types = SubjectService::subjectTypeArray();

        Result::success($types);
    }

    /**
     * 会计科目列表
     */
    public function listAction()
    {
        $get = $this->request->get();
        $page = $get['page'] ?: 1;
        $pageSize = $get['pageSize'] ?: 10;
        $param = [];
        if ($get['subjectCode']) $param['subject_code'] = $get['subjectCode'];
        if ($get['subjectName']) $param['subject_name'] = $get['subjectName'];
        if (!empty($get['subjectLevel'])) $param['subject_level'] = $get['subjectLevel'];
        if (!empty($get['subjectType'])) $param['subject_type'] = $get['subjectType'];

        $subjectService = new SubjectService();
        $result = $subjectService->getList($param, $page, $pageSize);

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
     * 获取父级科目列表
     */
    public function levelAction()
    {
        $level = trim($this->request->get('level'));
        $level = $level - 1;
        $param = [];
        $param['subject_level'] = $level;
        $subjectService = new SubjectService();
        $result = $subjectService->getList($param, 1, 1000);

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
     * 添加科目
     */
    public function addAction()
    {
        $post = $this->request->getJsonRawBody(true);
        if ($post['subject_level'] != 1 && empty($post['parent_subject'])) {
            Result::error(ResultCode::UNKNOWN, '请选择父级科目');
        }

        $param['subject_level'] = trim($post['subject_level']);
        $param['subject_code'] = trim($post['subject_code']);
        $param['subject_name'] = trim($post['subject_name']);
        $param['subject_type'] = trim($post['subject_type']);
        $param['balance_direction'] = trim($post['balance_direction']);
        $param['zero_flag'] = trim($post['zero_flag']);
        $param['oper_name'] = $this->user['name'];
        $param['subject_relationship_property'] = trim($post['subject_relationship_property']);
        $param['has_sub'] = trim($post['has_sub']);
        $param['oper_type'] = '1';
        if ($post['subject_level'] != 1) {
            $param['parent_subject'] = trim($post['parent_subject']);
            $param['parent_subject_name'] = trim($post['parent_subject_name']);
        }
        $subjectService = new SubjectService();
        $result = $subjectService->updateSubject($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::SUBJECTADD, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_INSERT_FAIL, '添加会计科目失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::SUBJECTADD, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 编辑
     */
    public function editAction()
    {
        $post = $this->request->getJsonRawBody(true);
        if (!strlen($post['subject_code']) || !strlen($post['subject_name'])) {
            Result::error(ResultCode::UNKNOWN, '科目代码或科目名称不能为空');
        }

        $param['subject_level'] = trim($post['subject_level']);
        $param['subject_code'] = trim($post['subject_code']);
        $param['subject_name'] = trim($post['subject_name']);
        $param['subject_type'] = trim($post['subject_type']);
        $param['balance_direction'] = trim($post['balance_direction']);
        $param['zero_flag'] = trim($post['zero_flag']);
        $param['oper_name'] = $this->user['name'];
        $param['subject_relationship_property'] = trim($post['subject_relationship_property']);
        $param['has_sub'] = trim($post['has_sub']);
        $param['oper_type'] = '2';
        $param['parent_subject'] = trim($post['parent_subject']);
        $param['parent_subject_name'] = trim($post['parent_subject_name']);

        $subjectService = new SubjectService();
        $result = $subjectService->updateSubject($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::SUBJECTEDIT, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '修改会计科目失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::SUBJECTEDIT, OperateLog::STATUS_SUCCESS);

        Result::success();
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $subjectCode = trim($post['subjectCode']);
        $param['subject_code'] = $subjectCode;
        $param['oper_type'] = '3';

        $subjectService = new SubjectService();
        $result = $subjectService->updateSubject($param);

        $log = new OperateLogService();
        if (!$result['status']){
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::SUBJECTDEL, OperateLog::STATUS_FAILED);
            Result::error(ResultCode::DB_UPDATE_FAIL, '删除会计科目失败');
        }
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::SUBJECTDEL, OperateLog::STATUS_SUCCESS);

        Result::success();
    }
}
