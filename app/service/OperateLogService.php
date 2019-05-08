<?php

namespace App\Service;

use App\Models\OperateLog;

class OperateLogService extends BaseService
{
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
