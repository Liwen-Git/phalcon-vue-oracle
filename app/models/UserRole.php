<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class UserRole extends Model
{
    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_user_role');
    }

    public function getSource()
    {
        return 't_user_role';
    }

    public function columnMap()
    {
        return [
            'AUTO_ID'  => 'auto_id',
            'USER_ID'  => 'user_id',
            'ROLE_ID'  => 'role_id',
        ];
    }
}
