<?php

namespace App\Service;

use App\Library\Common;
use App\Models\RoleMenuAct;
use App\Models\RoleNames;
use App\Models\UserRole;
use Phalcon\Exception;
use Phalcon\Paginator\Adapter\Model;

class RoleService extends BaseService
{

    /**
     * 获取角色表中的所有角色
     * @param bool $returnAll
     */
    public static function getRoleNames($returnAll = false)
    {
        if ($returnAll) {
            $roleNames = RoleNames::find();
        } else {
            $roleNames = RoleNames::find([
                'conditions' => 'status = ?1',
                'bind' => [
                    1 => RoleNames::STATUS_ON,
                ]
            ]);
        }
        if ($roleNames) $roleNames = $roleNames->toArray();
        return $roleNames;
    }

    /**
     * 获取角色列表
     * @param array $params
     * @param bool $returnAll
     * @return array|void
     */
    public static function getRoleList(array $params, $returnAll = false)
    {
        $roleId = array_get($params, 'roleId');
        $roleName = array_get($params, 'roleName');
        $status = array_get($params, 'status');
        $page = array_get($params, 'page', 1);
        $pageSize = array_get($params, 'pageSize', 10);

        $query = RoleNames::query();
        if ($roleId) {
            $query->andwhere('role_id = '. $roleId);
        }
        if ($roleName) {
            $query->andwhere('role_name like '. "'%$roleName%'");
        }
        if ($status) {
            $query->andwhere('status = '. $status);
        }
        $query->orderBy('to_number(role_id) desc');
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

    /**
     * 添加用户角色关系
     * @param $userId
     * @param $roleIds
     * @throws Exception
     */
    public function addUserRole($userId, $roleIds)
    {
        if (is_array($roleIds)) {
            foreach ($roleIds as $roleId) {
                $userRole = new UserRole();
                $userRole->auto_id = $this->getNextId(self::DB_CRM, 'seq_user_role_id');
                $userRole->user_id = $userId;
                $userRole->role_id = $roleId;
                if ($userRole->save() === false) {
                    throw new Exception('添加用户所属角色失败');
                }
            }
        } else {
            throw new Exception('所属角色错误');
        }
    }

    /**
     * 通过用户id 删除用户角色关系
     * @param $userId
     * @throws Exception
     */
    public function deleteUserRoleByUserId($userId)
    {
        $roles = UserRole::find([
            "user_id = '{$userId}'",
        ]);

        if ($roles) {
            foreach ($roles as $role) {
                if ($role->delete() === false) {
                    throw new Exception('编辑用户时，删除角色失败');
                }
            }
        }
    }

    /**
     * 编辑用户角色关系
     * @param $userId
     * @param $roleIds
     * @throws Exception
     */
    public function editUserRole($userId, $roleIds)
    {
        $this->deleteUserRoleByUserId($userId);
        $this->addUserRole($userId, $roleIds);
    }

    /**
     * 权限菜单列表
     * @return array
     */
    public function getActionMenu()
    {
        $sql = "SELECT menu_id AS id, parent_id, menu_name AS name, 0 as is_action FROM t_menus 
                UNION ALL ( select 'action_' || action_id as id, menu_id as parent_id, action_name as name , 1 as is_action from t_menu_actions )
                ORDER BY parent_id,id ASC";
        $list = $this->getDI()->get(self::DB_CRM)->query($sql)->fetchAll();
        foreach ($list as &$item) {
            $item = array_change_key_case($item, CASE_LOWER);
        }
        $menu = Common::listToTree($list, 0, 'id', 'parent_id', 'sub');
        return $menu;
    }

    /**
     * 角色表中添加角色数据
     * @param array $param
     * @return RoleNames
     * @throws Exception
     */
    public function addRoleNames(array $param)
    {
        $roleName = new RoleNames();
        $roleName->role_id = self::getNextId(self::DB_CRM, 'seq_rolenames_id');
        $roleName->role_name = $param['roleName'];
        $roleName->status = $param['status'];
        $roleName->remark = $param['remark'];
        $roleName->lastuptname = $this->user['name'];
        $roleName->lastupttime = date('Y-m-d H:i:s');

        if ($roleName->save() === false) {
            throw new Exception('添加角色失败');
        }
        return $roleName;
    }

    /**
     * 角色菜单权限表中添加权限
     * @param $roleId
     * @param $actions
     * @throws Exception
     */
    public function addRoleMenuAct($roleId, $actions)
    {
        if (!empty($actions)) {
            foreach ($actions as $action) {
                if ($action['is_action'] == 1) {
                    $roleMenuAct = new RoleMenuAct();
                    $roleMenuAct->role_menu_id = self::getNextId(self::DB_CRM, 'seq_role_menu_act_id');
                    $roleMenuAct->role_id = $roleId;
                    $roleMenuAct->menu_id = $action['parent_id'];
                    $roleMenuAct->action_id = substr($action['id'], 7);
                    $roleMenuAct->lastuptname = $this->user['name'];
                    $roleMenuAct->lastupttime = date('Y-m-d H:i:s');

                    if ($roleMenuAct->save() === false) {
                        throw new Exception('添加角色所属权限失败');
                    }
                }
            }
        }
    }
}
