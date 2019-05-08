<?php

namespace App\Models;

use Phalcon\Db\Column;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData;

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

    public function metaData()
    {
        return array(
            // Every column in the mapped table 表字段
            MetaData::MODELS_ATTRIBUTES => [
                'USER_ID',
                'ACCOUNT',
                'NAME',
                'PASSWORD',
                'PHONE',
                'STATUS',
                'LASTUPTNAME',
                'LASTUPTTIME',
                'LASTLOGINTIME',
                'COUNT_LOGIN',
                'LAST_LOGIN_IP',
                'PWD_ERR_NUM',
                'LOGIN_ERR_DATE',
                'PWD_UPDATE_DATE',
            ],

            // Every column part of the primary key 主键
            MetaData::MODELS_PRIMARY_KEY => [
                'USER_ID',
            ],

            // Every column that isn't part of the primary key 非主键
            MetaData::MODELS_NON_PRIMARY_KEY => [
                'ACCOUNT',
                'NAME',
                'PASSWORD',
                'PHONE',
                'STATUS',
                'LASTUPTNAME',
                'LASTUPTTIME',
                'LASTLOGINTIME',
                'COUNT_LOGIN',
                'LAST_LOGIN_IP',
                'PWD_ERR_NUM',
                'LOGIN_ERR_DATE',
                'PWD_UPDATE_DATE',
            ],

            // Every column that doesn't allows null values 不为null的字段
            MetaData::MODELS_NOT_NULL => [
                'USER_ID',
                'ACCOUNT',
                'NAME',
                'PASSWORD',
            ],

            // Every column and their data types 每个字段类型
            MetaData::MODELS_DATA_TYPES => [
                'USER_ID' => Column::TYPE_VARCHAR,
                'ACCOUNT' => Column::TYPE_VARCHAR,
                'NAME' => Column::TYPE_VARCHAR,
                'PASSWORD' => Column::TYPE_VARCHAR,
                'PHONE' => Column::TYPE_VARCHAR,
                'STATUS' => Column::TYPE_VARCHAR,
                'LASTUPTNAME' => Column::TYPE_VARCHAR,
                'LASTUPTTIME' => Column::TYPE_VARCHAR,
                'LASTLOGINTIME' => Column::TYPE_VARCHAR,
                'COUNT_LOGIN' => Column::TYPE_INTEGER,
                'LAST_LOGIN_IP' => Column::TYPE_VARCHAR,
                'PWD_ERR_NUM' => Column::TYPE_INTEGER,
                'LOGIN_ERR_DATE' => Column::TYPE_VARCHAR,
                'PWD_UPDATE_DATE' => Column::TYPE_VARCHAR,
            ],

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => [

            ],

            // The identity column, use boolean false if the model doesn't have
            // an identity column
            MetaData::MODELS_IDENTITY_COLUMN => false,

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => [
                'USER_ID' => Column::BIND_PARAM_STR,
                'ACCOUNT' => Column::BIND_PARAM_STR,
                'NAME' => Column::BIND_PARAM_STR,
                'PASSWORD' => Column::BIND_PARAM_STR,
                'PHONE' => Column::BIND_PARAM_STR,
                'STATUS' => Column::BIND_PARAM_STR,
                'LASTUPTNAME' => Column::BIND_PARAM_STR,
                'LASTUPTTIME' => Column::BIND_PARAM_STR,
                'LASTLOGINTIME' => Column::BIND_PARAM_STR,
                'COUNT_LOGIN' => Column::BIND_PARAM_INT,
                'LAST_LOGIN_IP' => Column::BIND_PARAM_STR,
                'PWD_ERR_NUM' => Column::BIND_PARAM_INT,
                'LOGIN_ERR_DATE' => Column::BIND_PARAM_STR,
                'PWD_UPDATE_DATE' => Column::BIND_PARAM_STR,
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
