<?php


namespace App\Controllers;


use App\Library\Result;

class LoginController extends ControllerBase
{
    public function loginAction()
    {
        Result::success(['log' => 'info']);
    }
}
