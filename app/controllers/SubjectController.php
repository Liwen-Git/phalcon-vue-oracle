<?php

namespace App\Controllers;

use App\Library\Result;
use App\Library\ResultCode;
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
        if ($get['subjectLevel']) $param['subject_level'] = $get['subjectLevel'];
        if ($get['subjectType']) $param['subject_type'] = $get['subjectType'];

        $subjectService = new SubjectService();
        $result = $subjectService->getList($param, $page, $pageSize);

        if (!$result['status']) {
            Result::error(ResultCode::UNKNOWN, '数据获取失败');
        }

        Result::success($result['data']);
    }
}
