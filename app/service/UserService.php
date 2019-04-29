<?php

namespace App\Service;

use App\Library\Common;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\Menus;
use App\Models\Users;

class UserService extends BaseService
{
    const USER_KEY = 'user_key';
    const SESSION_KEY = 'session_key';

    public function checkPasswordByAccount($account, $password)
    {
        $user = self::getByAccount($account);
        if (!$user) {
            Result::error(ResultCode::ACCOUNT_NOT_FOUND, '账号不存在');
        }
        if ($user['password'] == md5(md5($password))) {
            $sessionId = $this->session->getID();
            $this->redis->hset(self::USER_KEY, $sessionId, json_encode($user));
            $this->redis->hset(self::SESSION_KEY, $user['user_id'], $sessionId);
            $this->redis->expire(self::USER_KEY, 8 * 60 * 60);
            $this->redis->expire(self::SESSION_KEY, 8 * 60 * 60);
        } else {
            Result::error(ResultCode::ACCOUNT_PASSWORD_ERROR, '密码错误');
        }

        return $user;
    }

    public static function getByAccount($account)
    {
        $user = Users::query()->where('account = :account:')
            ->bind(['account' => $account])
            ->execute()
            ->getFirst();
        if ($user) {
            $user = $user->toArray();
        }
        return $user;
    }

    public function getUserMenuAndRules(array $user)
    {
        $menuArray = self::getMenu();

        return $menuArray;
    }

    /**
     * 获取菜单 树形结构
     * @return array|void
     */
    public static function getMenu()
    {
        $menu = Menus::find([
            "status = 1",
            'order' => 'ordernum,menu_id asc',
        ]);
        if ($menu) $menu = $menu->toArray();
        $menu = Common::listToTree($menu, 0, 'menu_id', 'parent_id');

        return $menu;
    }
}
