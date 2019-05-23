<?php

namespace App\Service;

class BusinessTypeService extends BaseService
{

    /**
     * 获取所有的业务类型(新)
     * @param string $type 0:渠道、报表模块 1:其它模块
     * @param string $level
     * @return array
     */
    public function getBusinessTypesOfNew($type = '0', $level = '3')
    {
        $param['type'] = $type;
        $param['interface_type'] = 'qryclassbusitype';
        $result = $this->postHttp('ledger', $param);

        if (!$result['status']) {
            return $this->makeBack('获取数据失败[业务类型]');
        }
        if ($result['data']['total'] < 1) {
            return $this->makeBack('业务类型无数据');
        }

        $res = [];
        foreach ($result['data']['list'] as $k => $v){
            $tmp = [];
            $tmp['type'] = $v['busi_type'];
            $tmp['name'] = $v['busi_name'];
            if($level != '1'){
                foreach ($v['second_busi_info'] as $k1 => $v1){
                    $tmp1 = [];
                    $tmp1['type'] = $v1['second_busi_type'];
                    $tmp1['name'] = $v1['second_busi_name'];
                    if($level != '2'){
                        foreach ($v1['sub_busi_info'] as $k2 => $v2){
                            $tmp2 = [];
                            $tmp2['type'] = $v2['sub_busi_type'];
                            $tmp2['name'] = $v2['sub_busi_name'];
                            $tmp1['child'][] = $tmp2;
                        }
                    }
                    $tmp['child'][] = $tmp1;
                }
            }
            $res[] = $tmp;
        }

        return $this->makeBack("获取成功[业务类型]", true, $res);
    }

    /**
     * 业务类型列表
     * @param array $param
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getList(array $param, $page = 1, $pageSize = 10)
    {
        $param['interface_type'] = 'queryBusiType';
        $param['page_index'] = (int)$page;
        $param['page_num'] = (int)$pageSize;
        $result = $this->postHttp('ledger', $param);
        if (!$result['status']) {
            return $this->makeBack('数据获取失败[业务类型列表]');
        }

        return $this->makeBack('获取成功[业务类型列表]', true, $result['data']);
    }

    /**
     * 添加、编辑业务类型
     * @param $data
     * @return array
     */
    public function addAndEditBusinessType($data)
    {
        $data['interface_type'] = 'updateBusiType';
        $result = $this->postHttp('ledger', $data);
        if (!$result['status']) {
            return $this->makeBack('添加或编辑业务类型失败');
        }

        return $this->makeBack('添加或编辑业务类型成功', true, $result['data']);
    }

    /**
     * 通过id获取 获取业务大类
     * @param $id
     * @return array|bool|mixed
     */
    public function getBizTypes($id)
    {
        $key = "Cache:Biztype:T_".$id;
        $bizType = json_decode($this->redis->get($key), true);
        if (!$bizType) {
            $sql = "select distinct(busi_type), busy_type_name from t_acc_busitype where busi_type = {$id}";
            $result = $this->getDI()->get(self::DB_ACC)->query($sql)->fetch();
            if (!$result) {
                return false;
            }
            $result = array_change_key_case($result, CASE_LOWER);
            $this->redis->set($key, json_encode([
                $result['busi_type'] => $result['busy_type_name']
            ], 320), 86400);
            $bizType = [
                $result['busi_type'] => $result['busy_type_name']
            ];
        }
        return $bizType;
    }

    /**
     * 获取所有业务大类
     * @return array
     */
    public function getAllBizTypes()
    {
        $key = "Cache:Biztype:All";
        $bizType = json_decode($this->redis->get($key), true);
        if (!$bizType) {
            $sql = "select distinct(busi_type), busy_type_name from t_acc_busitype";
            $result = $this->getDI()->get(self::DB_ACC)->query($sql)->fetchAll();
            if (!$result) {
                return $this->makeBack("业务大类无数据");
            }
            $bizType = [];
            foreach ($result as $item) {
                $item = array_change_key_case($item, CASE_LOWER);
                $bizType[$item['busi_type']] = $item['busy_type_name'];
            }
            $this->redis->set($key,json_encode($bizType,320),86400);
        }
        return $this->makeBack('获取成功[获取所有业务大类]', true, $bizType);
    }
}
