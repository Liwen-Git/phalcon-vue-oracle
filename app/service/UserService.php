<?php


namespace App\Service;


use App\Models\Users;

class UserService extends BaseService
{
    public static function checkPasswordByAccount($account, $password)
    {
        $user = self::getByAccount($account);
        return $user;
    }

    public static function getByAccount($account)
    {
        $user = Users::findFirst(['account' => $account]);
        return $user;
    }
}
