<?php

namespace App\Models;

use Phalcon\Db\Column;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData;

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

    public function metaData()
    {
        return array(
            // Every column in the mapped table 表字段
            MetaData::MODELS_ATTRIBUTES => [
                'AUTO_ID',
                'USER_ID',
                'ROLE_ID',
            ],

            // Every column part of the primary key 主键
            MetaData::MODELS_PRIMARY_KEY => [
                'AUTO_ID',
            ],

            // Every column that isn't part of the primary key 非主键
            MetaData::MODELS_NON_PRIMARY_KEY => [
                'USER_ID',
                'ROLE_ID',
            ],

            // Every column that doesn't allows null values 不为null的字段
            MetaData::MODELS_NOT_NULL => [
                'AUTO_ID',
            ],

            // Every column and their data types 每个字段类型
            MetaData::MODELS_DATA_TYPES => [
                'AUTO_ID' => Column::TYPE_VARCHAR,
                'USER_ID' => Column::TYPE_VARCHAR,
                'ROLE_ID' => Column::TYPE_VARCHAR,
            ],

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => [

            ],

            // The identity column, use boolean false if the model doesn't have
            // an identity column
            MetaData::MODELS_IDENTITY_COLUMN => false,

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => [
                'AUTO_ID' => Column::BIND_PARAM_STR,
                'USER_ID' => Column::BIND_PARAM_STR,
                'ROLE_ID' => Column::BIND_PARAM_STR,
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
