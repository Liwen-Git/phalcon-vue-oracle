<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BaseService;
use App\Service\OperateLogService;
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

    /**
     * 角色列表
     */
    public function listAction()
    {
        $param = $this->request->get();
        $roleId = (int)$param['role_id'];
        $roleName = trim($param['role_name']);
        $status = isset($param['status']) ? (int)$param['status'] : '';
        $pageSize = (int)$param['pageSize'];
        $page = (int)$param['page'];

        $params = compact('roleId', 'roleName', 'status', 'page', 'pageSize');
        $list = RoleService::getRoleList($params);

        Result::success([
            'list' => $list->items,
            'total' => $list->total_items,
        ]);
    }

    /**
     * 角色管理中的 权限菜单列表
     */
    public function action_menuAction()
    {
        $roleService = new RoleService();
        $menu = $roleService->getActionMenu();

        Result::success($menu);
    }

    /**
     * 添加角色
     */
    public function addAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $roleName = trim($post['roleName']);
        $status = (int)$post['status'];
        $remark = trim($post['remark']);
        $actions = $post['actions'];

        $log = new OperateLogService();
        $this->getDI()->get(BaseService::DB_CRM)->begin();
        $role = [];
        try {
            $roleService = new RoleService();
            $param = compact('roleName', 'status', 'remark');
            $role = $roleService->addRoleNames($param);

            $roleService->addRoleMenuAct($role->role_id, $actions);

            $this->getDI()->get(BaseService::DB_CRM)->commit();
        } catch (\Exception $e) {
            $this->getDI()->get(BaseService::DB_CRM)->rollback();
            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::ROLEADD, OperateLog::STATUS_FAILED);
            $msg = $e->getMessage() ?: '角色添加失败';
            Result::error(ResultCode::DB_INSERT_FAIL, $msg);
        }

        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::ROLEADD, OperateLog::STATUS_SUCCESS);
        Result::success($role);
    }
}
