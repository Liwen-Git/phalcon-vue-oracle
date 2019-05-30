<?php


namespace App\Controllers;

use App\Library\Result;
use App\Service\UserService;

class SelfController extends ControllerBase
{
    /**
     * 登录
     */
    public function loginAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $account = $post['account'];
        $password = $post['password'];

        $userService = new UserService();
        $user = $userService->checkPasswordByAccount($account, $password);

        $menuAndRules = $userService->getUserMenuAndRules($user);

        Result::success([
            'user'  => $user,
            'menus' => $menuAndRules['menuArray'],
            'rules' => $menuAndRules['rules'],
            'forbiddenRules' => $menuAndRules['forbiddenRules'],
        ]);
    }

    /**
     * 退出
     */
    public function logoutAction()
    {
        $userService = new UserService();
        $userService->logout();

        Result::success();
    }
}
