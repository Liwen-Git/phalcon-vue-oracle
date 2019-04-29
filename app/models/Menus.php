<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Menus extends Model
{

    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_menus');
    }

    public function getSource()
    {
        return 't_menus';
    }

    public function columnMap()
    {
        return [
            'MENU_ID'      => 'menu_id',
            'PARENT_ID'    => 'parent_id',
            'MENU_NAME'    => 'menu_name',
            'MENU_URL'     => 'menu_url',
            'MENU_TYPE'    => 'menu_type',
            'JUMP_URL'     => 'jump_url',
            'ORDERNUM'     => 'ordernum',
            'STATUS'       => 'status',
            'LASTUPTNAME'  => 'lastuptname',
            'LASTUPTTIME'  => 'lastupttime',
            'CSSSTYLE'     => 'cssstyle',
        ];
    }
}
