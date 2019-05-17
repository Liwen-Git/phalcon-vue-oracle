<?php

namespace App\Service;

class SubjectService extends BaseService
{
    /**
     * 科目级别
     * @return array
     */
    public static function subjectLevelArray()
    {
        $levels = array(
            '1' => "一级科目",
            '2' => "二级科目",
            '3' => "三级科目",
            '4' => "四级科目",
        );
        return $levels;
    }

    /**
     * 科目类型
     * @return array
     */
    public static function subjectTypeArray()
    {
        $types = array(
            '1' => "资产类",
            '2' => "负债类",
            '3' => "共同类",
            '4' => "权益类",
            '5' => "成本类",
            '6' => "损益类",
        );
        return $types;
    }

    /**
     * 获取会计科目列表
     * @param $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getList($param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = "querySubjectCode";
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('数据获取失败[会计科目列表]');
        }
        if($result['data']['total'] < 1){
            return $this->makeBack("无数据[会计科目列表]");
        }

        return $this->makeBack("获取成功[会计科目列表]",true, $result['data']);
    }

    /**
     * @param $param
     * @return array
     */
    public function updateSubject($param)
    {
        $param['interface_type'] = 'updateSubjectCode';
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('添加或修改科目时失败');
        }

        return $result;
    }
}
