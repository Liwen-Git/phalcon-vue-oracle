<?php

namespace App\Service;

use App\Models\RoleNames;

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
}
