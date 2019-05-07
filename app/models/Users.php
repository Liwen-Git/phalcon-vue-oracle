<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Users extends Model
{
    const STATUS_ON = 1;

    public $account;

    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_users');
    }

    public function getSource()
    {
        return 't_users';
    }

    public function columnMap()
    {
        return [
            'USER_ID'          => 'user_id',
            'ACCOUNT'          => 'account',
            'NAME'             => 'name',
            'PASSWORD'         => 'password',
            'PHONE'            => 'phone',
            'STATUS'           => 'status',
            'LASTUPTNAME'      => 'lastuptname',
            'LASTUPTTIME'      => 'lastupttime',
            'LASTLOGINTIME'    => 'lastlogintime',
            'COUNT_LOGIN'      => 'count_login',
            'LAST_LOGIN_IP'    => 'last_login_ip',
            'PWD_ERR_NUM'      => 'pwd_err_num',
            'LOGIN_ERR_DATE'   => 'login_err_date',
            'PWD_UPDATE_DATE'  => 'pwd_update_date',
        ];
    }
}
