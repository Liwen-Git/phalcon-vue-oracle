<?php

namespace App\Service;

use Phalcon\Di\Injectable;

class BaseService extends Injectable
{
    /**
     * service中数据库名
     */
    const DB_CRM = 'db_crm';
    const DB_ACC = 'db_acc';

    /**
     * 通过sequence获取oracle数据库表的主键id
     * @param $db
     * @param $seq
     * @return mixed
     */
    public function getNextId($db, $seq)
    {
        $sql = "select {$seq}.nextval as nextid from dual";
        $res = $this->getDI()->get($db)->query($sql)->fetch();

        return $res['NEXTID'];
    }
}
