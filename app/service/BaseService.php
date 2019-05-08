<?php

namespace App\Service;

use App\Library\Result;
use App\Library\ResultCode;
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

    /**
     * 登录过滤判断：判断是否登录或者登录是否过期
     * @param $uri
     */
    public function loginFilter($uri)
    {
        $publicUrls = [
            '/self/login',
            '/self/logout',
        ];

        if (!in_array($uri, $publicUrls)) {
            $sessionId = $this->session->getID();
            $user = $this->redis->hget(UserService::USER_KEY, $sessionId);
            if (empty($user)) {
                Result::error(ResultCode::UNLOGIN, '账号未登陆', ['login_url' => '/admin/login']);
            }
        }
    }
}
