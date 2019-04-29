<?php

namespace App\Service;

use App\Library\Result;
use App\Models\Users;

class UserService extends BaseService
{
    public static function checkPasswordByAccount($account, $password)
    {
        $user = self::getByAccount($account);
        if (!$user) {
            Result::error();
        }

        return $user;
    }

    public static function getByAccount($account)
    {
        $user = Users::query()->where('ACCOUNT = :account:')
            ->bind(['account' => $account])
            ->execute()
            ->getFirst();
        if ($user) {
            $user = $user->toArray();
            $user = array_change_key_case($user, CASE_LOWER);
        }
        return $user;
    }
}
