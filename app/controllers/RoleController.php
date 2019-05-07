<?php

namespace App\Controllers;

use App\Library\Result;
use App\Service\RoleService;

class RoleController extends ControllerBase
{
    /**
     * 获取所有角色
     */
    public function role_namesAction()
    {
        $roleNames = RoleService::getRoleNames();

        Result::success($roleNames);
    }
}
