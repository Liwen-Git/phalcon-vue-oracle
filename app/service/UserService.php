<?php

namespace App\Service;

use App\Library\Common;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\MenuActions;
use App\Models\Menus;
use App\Models\RoleMenuAct;
use App\Models\RoleNames;
use App\Models\UserRole;
use App\Models\Users;
use Phalcon\Paginator\Adapter\Model;

class UserService extends BaseService
{
    const USER_KEY = 'user_key';
    const SESSION_KEY = 'session_key';

    /**
     * 登录验证密码
     * @param $account
     * @param $password
     * @return mixed
     */
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

    /**
     * 通过账号获取用户
     * @param $account
     * @return mixed
     */
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

    /**
     * 获取用户菜单 和 权限
     * @param array $user
     * @return array
     */
    public function getUserMenuAndRules(array $user)
    {
        $menuArray = self::getMenu();
        $rules = self::getRulesByUserId($user['user_id']);

        $rules = array_values(array_unique(array_column($rules, 'action_url')));
        foreach ($rules as $key => $value) {
            $rules[$key] = strtolower($rules[$key]);
        }

        foreach ($menuArray as $key => $value) {
            foreach($value['sub'] as $k => $v){
                if (!in_array(strtolower($v['menu_url']), $rules)) {
                    unset($menuArray[$key]['sub'][$k]);
                }
            }
        }
        foreach ($menuArray as $key => $value) {
            $menuArray[$key]['sub'] = array_values($menuArray[$key]['sub']);
        }

        return [
            'menuArray' => $menuArray,
            'rules'     => $rules,
        ];
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

    /**
     * 获取用户的权限规则列表
     * @param $userId
     * @return mixed
     */
    public function getRulesByUserId($userId)
    {
        $user = Users::class;
        $userRole = UserRole::class;
        $roleMenuAct = RoleMenuAct::class;
        $menuActions = MenuActions::class;
        $menus = Menus::class;
        $builder = $this->modelsManager->createBuilder()
            ->columns('d.*')
            ->addfrom($user, 'a')
            ->leftJoin($userRole, "a.user_id = b.user_id", 'b')
            ->leftJoin($roleMenuAct, "c.role_id = b.role_id", 'c')
            ->leftJoin($menuActions, "d.action_id = c.action_id", 'd')
            ->leftJoin($menus, "e.menu_id = d.menu_id", 'e')
            ->where("e.status = 1 and d.status = 1");

        if ($userId != 1) {
            $builder = $builder->andWhere('a.user_id = '. $userId);
        }
        $builder = $builder->orderBy("c.action_id asc")
            ->getQuery()
            ->execute();
        if ($builder) {
            $builder = $builder->toArray();
        }

        return $builder;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $sessionId = $this->session->getID();
        $user = $this->redis->hget(self::USER_KEY, $sessionId);
        $user = json_decode($user, true);
        $this->redis->hdel(self::USER_KEY, $sessionId);
        $this->redis->hdel(self::SESSION_KEY, $user['user_id']);
    }

    /**
     * 获取用户列表
     * @param array $params
     * @param bool $returnAll
     * @return array|void
     */
    public static function getList(array $params, $returnAll = false)
    {
        $userId = array_get($params, 'userId');
        $name = array_get($params, 'name');
        $status = array_get($params, 'status');
        $page = array_get($params, 'page', 1);
        $pageSize = array_get($params, 'pageSize', 10);

        $query = Users::query();
        if ($userId) {
            $query->andwhere('user_id = '. $userId);
        }
        if ($name) {
            $query->andwhere('name like '. "'%$name%'");
        }
        if ($status) {
            $query->andwhere('status = '. $status);
        }
        $result = $query->execute();

        if ($returnAll) {
            if ($result) {
                return $result->toArray();
            } else {
                return [];
            }
        } else {
            $paginator = new Model([
                'data' => $result,
                'limit' => $pageSize,
                'page' => $page,
            ]);
            $result = $paginator->getPaginate();
            return $result;
        }
    }

    public function getRoleNamesByUserId($userId)
    {
        $userRole = UserRole::class;
        $roleNames = RoleNames::class;
        $builder = $this->modelsManager->createBuilder()
            ->columns('r.*')
            ->addfrom($userRole, 't')
            ->leftJoin($roleNames, "r.role_id = t.role_id", 'r')
            ->where('r.status = 1')
            ->andwhere('t.user_id = '. $userId)
            ->getQuery()
            ->execute();
        if ($builder) {
            $builder = $builder->toArray();
        }
        return $builder;
    }
}
