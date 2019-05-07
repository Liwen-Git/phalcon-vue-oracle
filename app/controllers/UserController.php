<?php

namespace App\Controllers;

use App\Library\Result;
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
        $status = $param['status'];
        $pageSize = $param['pageSize'];
        $page = $param['page'];

        $params = compact('userId', 'name', 'status', 'page', 'pageSize');
        $list = UserService::getList($params);

        $userService = new UserService();
        $data = [];
        foreach ($list->items as $k => $item) {
            $roleNames = $userService->getRoleNamesByUserId($item->user_id);
            $roleNames = !empty($roleNames) ? implode(',',array_column($roleNames, 'role_name')) : '';

            $data[$k] = $list->items[$k]->toArray();
            $data[$k]['role_name'] = $roleNames;
            $data[$k]['phone'] = substr_cut($item->phone, 3, 4);
        }

        Result::success([
            'list' => $data,
            'total' => $list->total_items,
        ]);
    }
}
