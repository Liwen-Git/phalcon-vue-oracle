<?php

namespace App\Controllers;

use App\Library\Result;
use App\Service\UserService;

class UserController extends ControllerBase
{
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
        foreach ($list->items as &$item) {
//            $item->role_name = $userService->getRoleNamesByUserId($item->user_id);
        }

        Result::success([
            'list' => $list->items,
            'total' => $list->total_items,
        ]);
    }
}
