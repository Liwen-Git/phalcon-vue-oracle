<?php

namespace App\Controllers;

use App\Library\OperateLogAction;
use App\Library\Result;
use App\Library\ResultCode;
use App\Models\OperateLog;
use App\Service\BaseService;
use App\Service\OperateLogService;
use App\Service\RoleService;
use App\Service\UserService;

class UserController extends ControllerBase
{
    /**
     * 用户管理列表
     */
    public function listAction()
    {
        $param = $this->request->get();
        $userId = $param['user_id'];
        $name = $param['name'];
        $status = isset($param['status']) ? $param['status'] : '';
        $pageSize = $param['pageSize'];
        $page = $param['page'];

        $params = compact('userId', 'name', 'status', 'page', 'pageSize');
        $list = UserService::getList($params);

        $userService = new UserService();
        $data = [];
        foreach ($list->items as $k => $item) {
            $roleNames = $userService->getRoleNamesByUserId($item->user_id);
            $roleIds = array_column($roleNames, 'role_id');
            $roleNames = !empty($roleNames) ? implode(',',array_column($roleNames, 'role_name')) : '';

            $data[$k] = $list->items[$k]->toArray();
            $data[$k]['role_name'] = $roleNames;
            $data[$k]['role_ids'] = $roleIds;
            $data[$k]['phone_cut'] = substr_cut($item->phone, 3, 4);
        }

        Result::success([
            'list' => $data,
            'total' => $list->total_items,
        ]);
    }

    /**
     * 用户添加
     */
    public function addAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $account = trim($post['account']);
        $name = trim($post['name']);
        $phone = trim($post['phone']);
        $password = trim($post['password']);
        $roleIds = $post['roleIds'];

        $checkAccount = UserService::getByAccount($account);
        if ($checkAccount) {
            Result::error(ResultCode::ACCOUNT_EXISTS, '添加用户失败,该用户名已被占用');
        }

        $log = new OperateLogService();

        $this->getDI()->get(BaseService::DB_CRM)->begin();
        $user = [];
        try {
            // 添加用户
            $data = compact('account', 'name', 'phone', 'password');
            $userService = new UserService();
            $user = $userService->addUser($data);

            // 添加用户角色关系
            $roleService = new RoleService();
            $roleService->addUserRole($user->user_id, $roleIds);

            $this->getDI()->get(BaseService::DB_CRM)->commit();
        } catch (\Exception $e) {
            $this->getDI()->get(BaseService::DB_CRM)->rollback();

            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USERADD, OperateLog::STATUS_FAILED);
            $msg = $e->getMessage() ?: '用户添加失败';
            Result::error(ResultCode::DB_INSERT_FAIL, $msg);
        }

        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USERADD, OperateLog::STATUS_SUCCESS);

        Result::success($user);
    }

    /**
     * 编辑用户
     */
    public function editAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $userId = (int)$post['userId'];
        $name = trim($post['name']);
        $phone = trim($post['phone']);
        $password = trim($post['password']);
        $roleIds = $post['roleIds'];
        $status = (int)$post['status'];

        $log = new OperateLogService();

        $this->getDI()->get(BaseService::DB_CRM)->begin();
        $user = [];
        try {
            // 编辑用户
            $userService = new UserService();
            $data = compact('name', 'phone', 'password', 'status');
            $user = $userService->editUser($userId, $data);

            // 编辑用户角色关系 (先删除后添加)
            $roleService = new RoleService();
            $roleService->editUserRole($userId, $roleIds);

            $this->getDI()->get(BaseService::DB_CRM)->commit();
        }catch (\Exception $e) {
            $this->getDI()->get(BaseService::DB_CRM)->rollback();

            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USEREDIT, OperateLog::STATUS_FAILED);
            $msg = $e->getMessage() ?: '编辑用户失败';
            Result::error(ResultCode::DB_UPDATE_FAIL, $msg);
        }

        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USEREDIT, OperateLog::STATUS_SUCCESS);

        Result::success($user);
    }

    /**
     * 删除用户
     */
    public function deleteAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $userId = (int)$post['userId'];

        $log = new OperateLogService();

        $this->getDI()->get(BaseService::DB_CRM)->begin();
        try {
            // 删除用户表中的用户
            $userService = new UserService();
            $userService->deleteUser($userId);

            // 删除用户角色关系表中的数据
            $roleService = new RoleService();
            $roleService->deleteUserRoleByUserId($userId);

            $this->getDI()->get(BaseService::DB_CRM)->commit();
        }catch (\Exception $e) {
            $this->getDI()->get(BaseService::DB_CRM)->rollback();

            $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USERDEL, OperateLog::STATUS_FAILED);
            $msg = $e->getMessage() ?: '删除用户失败';
            Result::error(ResultCode::DB_DELETE_FAIL, $msg);
        }

        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USERDEL, OperateLog::STATUS_SUCCESS);

        Result::success();
    }
}
