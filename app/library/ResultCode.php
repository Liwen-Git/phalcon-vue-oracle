<?php


namespace App\Library;


class ResultCode
{
    const SUCCESS = 0; // 成功

    const UNKNOWN = 500; // 未知错误

    const API_NOT_FOUND = 10000; // 接口不存在
    const PARAMS_INVALID = 10001; // 参数不合法
    const NO_PERMISSION = 10002; // 无权限操作

    const UNLOGIN = 10003;  // 未登录
    const ACCOUNT_NOT_FOUND = 10004; // 账号不存在
    const ACCOUNT_PASSWORD_ERROR = 10005;  // 账号密码错误
    const ACCOUNT_EXISTS = 10006;  // 账号已存在
    const TOKEN_INVALID = 10007;  // token无效
    const USER_ALREADY_BEEN_INVITE = 10008; // 用户已经被邀请过

    const DB_QUERY_FAIL = 10010;  // 数据库查询失败
    const DB_INSERT_FAIL = 10011;   // 数据库插入失败
    const DB_UPDATE_FAIL = 10012;  // 数据库更新失败
    const DB_DELETE_FAIL = 10013;  // 数据库删除失败


    const RULE_NOT_FOUND        = 10021;        // 权限不存在
    const AUTH_GROUP_NOT_FOUND  = 10022;        // 用户组不存在
    const RULE_SORT_DUPLICATED  = 10023;        // 权限排序不能重复
    const PERMISSION_NOT_ALLOWED = 10025;       // 无权限进行该操作
}
