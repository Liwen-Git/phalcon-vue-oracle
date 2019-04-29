<?php


namespace App\Controllers;

use App\Library\Result;
use App\Service\UserService;

class LoginController extends ControllerBase
{
    public function indexAction()
    {
        $post = $this->request->getJsonRawBody(true);
        $account = $post['account'];
        $password = $post['password'];

        $user = UserService::checkPasswordByAccount($account, $password);

        Result::success($user);
    }
}
