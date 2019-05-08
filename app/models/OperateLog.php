<?php

namespace App\Models;

use Phalcon\Db\Column;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData;

class OperateLog extends Model
{
    /**
     * 状态 1-成功 2-失败
     */
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;

    public function initialize()
    {
        $this->setConnectionService('db_crm');
        $this->setSchema('crm_user');
        $this->setSource('t_operate_log');
    }

    public function getSource()
    {
        return 't_operate_log';
    }

    public function columnMap()
    {
        return [
            'AUTOID'     => 'autoid',
            'USER_ID'    => 'user_id',
            'OPR_NAME'   => 'opr_name',
            'OPR_ACTION' => 'opr_action',
            'OPR_IP'     => 'opr_ip',
            'OPR_TIME'   => 'opr_time',
            'OPR_STATUS' => 'opr_status',
        ];
    }

    public function metaData()
    {
        return array(
            // Every column in the mapped table 表字段
            MetaData::MODELS_ATTRIBUTES => [
                'AUTOID',
                'USER_ID',
                'OPR_NAME',
                'OPR_ACTION',
                'OPR_IP',
                'OPR_TIME',
                'OPR_STATUS',
            ],

            // Every column part of the primary key 主键
            MetaData::MODELS_PRIMARY_KEY => [
                'AUTOID',
            ],

            // Every column that isn't part of the primary key 非主键
            MetaData::MODELS_NON_PRIMARY_KEY => [
                'USER_ID',
                'OPR_NAME',
                'OPR_ACTION',
                'OPR_IP',
                'OPR_TIME',
                'OPR_STATUS',
            ],

            // Every column that doesn't allows null values 不为null的字段
            MetaData::MODELS_NOT_NULL => [
                'AUTOID',
            ],

            // Every column and their data types 每个字段类型
            MetaData::MODELS_DATA_TYPES => [
                'AUTOID' => Column::TYPE_VARCHAR,
                'USER_ID' => Column::TYPE_VARCHAR,
                'OPR_NAME' => Column::TYPE_VARCHAR,
                'OPR_ACTION' => Column::TYPE_VARCHAR,
                'OPR_IP' => Column::TYPE_VARCHAR,
                'OPR_TIME' => Column::TYPE_DATE,
                'OPR_STATUS' => Column::TYPE_VARCHAR,
            ],

            // The columns that have numeric data types
            MetaData::MODELS_DATA_TYPES_NUMERIC => [

            ],

            // The identity column, use boolean false if the model doesn't have
            // an identity column
            MetaData::MODELS_IDENTITY_COLUMN => false,

            // How every column must be bound/casted
            MetaData::MODELS_DATA_TYPES_BIND => [
                'AUTOID' => Column::BIND_PARAM_STR,
                'USER_ID' => Column::BIND_PARAM_STR,
                'OPR_NAME' => Column::BIND_PARAM_STR,
                'OPR_ACTION' => Column::BIND_PARAM_STR,
                'OPR_IP' => Column::BIND_PARAM_STR,
                'OPR_TIME' => Column::BIND_PARAM_STR,
                'OPR_STATUS' => Column::BIND_PARAM_STR,
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
