<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class RoleNames extends Model
{
    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_rolenames');
    }

    public function getSource()
    {
        return 't_rolenames';
    }

    public function columnMap()
    {
        return [
            'ROLE_ID'      => 'role_id',
            'ROLE_NAME'    => 'role_name',
            'STATUS'       => 'status',
            'LASTUPTNAME'  => 'lastuptname',
            'LASTUPTTIME'  => 'lastupttime',
            'REMARK'       => 'remark',
        ];
    }}
