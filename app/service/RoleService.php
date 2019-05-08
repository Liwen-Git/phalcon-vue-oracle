<?php

namespace App\Service;

use App\Models\RoleNames;
use App\Models\UserRole;
use Phalcon\Exception;

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
}
