<?php

namespace App\Models;

use Phalcon\Db\Column;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData;

class RoleNames extends Model
{
    const STATUS_ON = 1;
    const STATUS_OFF = 2;

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
    }

    public function metaData()
    {
        return array(
            // Every column in the mapped table 表字段
            MetaData::MODELS_ATTRIBUTES => [
                'ROLE_ID',
                'ROLE_NAME',
                'STATUS',
                'LASTUPTNAME',
                'LASTUPTTIME',
                'REMARK',
            ],

            // Every column part of the primary key 主键
            MetaData::MODELS_PRIMARY_KEY => [
                'ROLE_ID',
            ],

            // Every column that isn't part of the primary key 非主键
            MetaData::MODELS_NON_PRIMARY_KEY => [
                'ROLE_NAME',
                'STATUS',
                'LASTUPTNAME',
                'LASTUPTTIME',
                'REMARK',
            ],

            // Every column that doesn't allows null values 不为null的字段
            MetaData::MODELS_NOT_NULL => [
                'ROLE_ID',
                'ROLE_NAME',
                'STATUS',
            ],

            // Every column and their data types 每个字段类型
            MetaData::MODELS_DATA_TYPES => [
                'ROLE_ID' => Column::TYPE_VARCHAR,
                'ROLE_NAME' => Column::TYPE_VARCHAR,
                'STATUS' => Column::TYPE_VARCHAR,
                'LASTUPTNAME' => Column::TYPE_VARCHAR,
                'LASTUPTTIME' => Column::TYPE_VARCHAR,
                'REMARK' => Column::TYPE_VARCHAR,
            ],

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => [

            ],

            // The identity column, use boolean false if the model doesn't have
            // an identity column
            MetaData::MODELS_IDENTITY_COLUMN => false,

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => [
                'ROLE_ID' => Column::BIND_PARAM_STR,
                'ROLE_NAME' => Column::BIND_PARAM_STR,
                'STATUS' => Column::BIND_PARAM_STR,
                'LASTUPTNAME' => Column::BIND_PARAM_STR,
                'LASTUPTTIME' => Column::BIND_PARAM_STR,
                'REMARK' => Column::BIND_PARAM_STR,
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
