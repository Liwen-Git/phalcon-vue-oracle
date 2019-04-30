<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class RoleMenuAct extends Model
{
    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_role_menu_act');
    }

    public function getSource()
    {
        return 't_role_menu_act';
    }

    public function columnMap()
    {
        return [
            'ROLE_MENU_ID' => 'role_menu_id',
            'ROLE_ID'      => 'role_id',
            'MENU_ID'      => 'menu_id',
            'ACTION_ID'    => 'action_id',
            'LASTUPTNAME'  => 'lastuptname',
            'LASTUPTTIME'  => 'lastupttime',
        ];
    }
}
