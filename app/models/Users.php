<?php


namespace App\Models;


use Phalcon\Mvc\Model;

class Users extends Model
{
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
}
