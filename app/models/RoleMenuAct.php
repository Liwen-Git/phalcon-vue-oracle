<?php

namespace App\Models;

use Phalcon\Db\Column;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData;

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

    public function metaData()
    {
        return array(
            // Every column in the mapped table 表字段
            MetaData::MODELS_ATTRIBUTES => [
                'ROLE_MENU_ID',
                'ROLE_ID',
                'MENU_ID',
                'ACTION_ID',
                'LASTUPTNAME',
                'LASTUPTTIME',
            ],

            // Every column part of the primary key 主键
            MetaData::MODELS_PRIMARY_KEY => [
                'ROLE_MENU_ID',
            ],

            // Every column that isn't part of the primary key 非主键
            MetaData::MODELS_NON_PRIMARY_KEY => [
                'ROLE_ID',
                'MENU_ID',
                'ACTION_ID',
                'LASTUPTNAME',
                'LASTUPTTIME',
            ],

            // Every column that doesn't allows null values 不为null的字段
            MetaData::MODELS_NOT_NULL => [
                'ROLE_MENU_ID',
                'ROLE_ID',
                'MENU_ID',
                'ACTION_ID',
            ],

            // Every column and their data types 每个字段类型
            MetaData::MODELS_DATA_TYPES => [
                'ROLE_MENU_ID' => Column::TYPE_VARCHAR,
                'ROLE_ID' => Column::TYPE_VARCHAR,
                'MENU_ID' => Column::TYPE_VARCHAR,
                'ACTION_ID' => Column::TYPE_VARCHAR,
                'LASTUPTNAME' => Column::TYPE_VARCHAR,
                'LASTUPTTIME' => Column::TYPE_VARCHAR,
            ],

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => [

            ],

            // The identity column, use boolean false if the model doesn't have
            // an identity column
            MetaData::MODELS_IDENTITY_COLUMN => false,

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => [
                'ROLE_MENU_ID' => Column::BIND_PARAM_STR,
                'ROLE_ID' => Column::BIND_PARAM_STR,
                'MENU_ID' => Column::BIND_PARAM_STR,
                'ACTION_ID' => Column::BIND_PARAM_STR,
                'LASTUPTNAME' => Column::BIND_PARAM_STR,
                'LASTUPTTIME' => Column::BIND_PARAM_STR,
            ],

            // Fields that must be ignored from INSERT SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [

            ],

            // Fields that must be ignored from UPDATE SQL statements
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [

            ],

            // Default values for columns
            MetaData::MODELS_DEFAULT_VALUES => [

            ],

            // Fields that allow empty strings
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
        );
    }
}
