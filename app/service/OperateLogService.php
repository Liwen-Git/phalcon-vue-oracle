<?php

namespace App\Service;

use App\Models\OperateLog;
use Phalcon\Paginator\Adapter\NativeArray;

class OperateLogService extends BaseService
{
    /**
     * 日志列表 通过sql进行分页
     * @param array $params
     * @param bool $returnAll
     * @return array|void
     */
    public function getList(array $params, $returnAll = false)
    {
        $userId = array_get($params, 'userId');
        $action = array_get($params, 'action');
        $status = array_get($params, 'status');
        $startDate = array_get($params, 'startDate');
        $endDate = array_get($params, 'endDate');
        $page = array_get($params, 'page', 1);
        $pageSize = array_get($params, 'pageSize', 10);

        $where = '1 = 1';
        if ($userId) {
            $where .= " and user_id = '{$userId}'";
        }
        if ($action) {
            $where .= " and opr_action like '%{$action}%'";
        }
        if ($status) {
            $where .= " and opr_status = '{$status}'";
        }
        if ($startDate && $endDate) {
            $where .= " and opr_time >= to_date('$startDate','YYYY-MM-DD hh24:mi:ss') and opr_time <= to_date('$endDate', 'YYYY-MM-DD hh24:mi:ss')";
        } elseif ($startDate) {
            $where .= " and opr_time >= to_date('$startDate','YYYY-MM-DD hh24:mi:ss')";
        } elseif ($endDate) {
            $where .= " and opr_time <= to_date('$endDate', 'YYYY-MM-DD hh24:mi:ss')";
        }

        $sql = "select user_id, opr_name, opr_action, opr_ip, to_char(opr_time,'yyyy-mm-dd HH24:mi:ss') as opr_time, opr_status from t_operate_log where {$where} order by opr_time desc";
        $result = $this->getDI()->get(self::DB_CRM)->query($sql)->fetchAll();

        if ($returnAll) {
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } else {
            $paginator = new NativeArray([
                'data' => $result,
                'limit' => $pageSize,
                'page' => $page,
            ]);
            return $paginator->getPaginate();
        }
    }

    /**
     * 添加操作日志
     * @param $userId
     * @param $operateName
     * @param $operateAction
     * @param $status
     * @return OperateLog
     */
    public function addOperateLog($userId, $operateName, $operateAction, $status)
    {
        $log = new OperateLog();
        $log->autoid = self::getNextId(self::DB_CRM, 'seq_operate_log_id');
        $log->user_id = $userId;
        $log->opr_name = $operateName;
        $log->opr_action = $operateAction;
        $log->opr_ip = get_client_ip();
        $log->opr_status = $status;
        $log->save();

        $time = date('Y-m-d H:i:s', time());
        $sql = "update t_operate_log set opr_time = to_date('{$time}', 'yyyy-mm-dd hh24:mi:ss') where autoid = '{$log->autoid}'";
        $this->getDI()->get(self::DB_CRM)->query($sql);

        return $log;
    }
}
