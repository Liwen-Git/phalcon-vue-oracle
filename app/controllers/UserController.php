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

        $this->getDI()->get(BaseService::DB_CRM)->begin();
        $user = [];
        try {
            $data = compact('account', 'name', 'phone', 'password');
            $userService = new UserService();
            $user = $userService->addUser($data);

            $roleService = new RoleService();
            $roleService->addUserRole($user->user_id, $roleIds);

            $this->getDI()->get(BaseService::DB_CRM)->commit();
        } catch (\Exception $e) {
            $this->getDI()->get(BaseService::DB_CRM)->rollback();
            Result::error(ResultCode::DB_INSERT_FAIL, $e->getMessage());
        }
        $log = new OperateLogService();
        $log->addOperateLog($this->user['user_id'], $this->user['account'], OperateLogAction::USERADD, OperateLog::STATUS_SUCCESS);

        Result::success($user);
    }
}
