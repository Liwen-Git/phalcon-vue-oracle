<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class MenuActions extends Model
{
    public $action_url;

    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_menu_actions');
    }

    public function getSource()
    {
        return 't_menu_actions';
    }

    public function columnMap()
    {
        return [
            'ACTION_ID'    => 'action_id',
            'ACTION_NAME'  => 'action_name',
            'ACTION_URL'   => 'action_url',
            'STATUS'       => 'status',
            'MENU_ID'      => 'menu_id',
            'LASTUPTNAME'  => 'lastuptname',
            'LASTUPTTIME'  => 'lastupttime',
        ];
    }
}
