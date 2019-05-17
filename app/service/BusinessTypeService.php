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
}
