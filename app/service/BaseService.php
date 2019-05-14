<?php

namespace App\Service;

use App\Library\Result;
use App\Library\ResultCode;
use App\Library\TfbApi;
use Exception;
use Phalcon\Di\Injectable;

class BaseService extends Injectable
{
    /**
     * service中数据库名
     */
    const DB_CRM = 'db_crm';
    const DB_ACC = 'db_acc';

    /**
     * 接口版本
     */
    const API_VERSION = '1.0.0';

    /**
     * 通过sequence获取oracle数据库表的主键id
     * @param $db
     * @param $seq
     * @return mixed
     */
    public function getNextId($db, $seq)
    {
        $sql = "select {$seq}.nextval as nextid from dual";
        $res = $this->getDI()->get($db)->query($sql)->fetch();

        return $res['NEXTID'];
    }

    /**
     * 登录过滤判断：判断是否登录或者登录是否过期
     * @param $uri
     */
    public function loginFilter($uri)
    {
        $publicUrls = [
            '/self/login',
            '/self/logout',
        ];

        if (!in_array($uri, $publicUrls)) {
            $sessionId = $this->session->getID();
            $user = $this->redis->hget(UserService::USER_KEY, $sessionId);
            if (empty($user)) {
                Result::error(ResultCode::UNLOGIN, '账号未登陆', ['login_url' => '/admin/login']);
            }
        }
    }

    /**
     * 请求api
     * @param $name
     * @param $param
     * @param string $isAll
     * @return array
     */
    public function postHttp($name, $param, $isAll = '0')
    {
        $param['api_ver'] = self::API_VERSION;
        $isDebug = $this->config->isDebug;
        $urls = TfbApi::url();
        $url = $urls[$isDebug][$name];

        $res = $this->curl($url, json_encode($param,320));
        $response = json_decode($res,true);

        if ($response['code'] != '00') {
            return [
                'status' => false,
                'msg' => $response['msg'],
            ];
        }

        if($isAll == '1'){
            return [
                "status" => true,
                "msg" => "获取成功",
                "data" => $response
            ];
        }else{
            return [
                "status" => true,
                "msg" => "获取成功",
                "data" => [
                    "total" => $response['total'] ? $response['total'] : count($response['data']),
                    "list" => $response['data']
                ]
            ];
        }
    }

    /**
     * curl请求
     * @param $url
     * @param $jsonStr
     * @param array $header
     * @return bool|string
     */
    public function curl($url, $jsonStr, $header = [])
    {
        if (!is_null(json_decode($jsonStr))){
            $header[] =  'Content-Type: application/json; charset=utf-8';
            $header[] =  'Content-Length: ' . strlen($jsonStr);
        }
        $ssl = substr($url, 0, 8) == "https://" ? true : false;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if ($ssl) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $this->writeLog("http_post_json URL [".$url."]");
            $this->writeLog("http_post_json HTTPCODE [".$httpCode."]");
            $this->writeLog("http_post_json DATA", $jsonStr);
            $this->writeLog("http_post_json RESULT", $response);

            curl_close($ch);
        } catch (Exception $e) {
            $this->writeLog("http_post_json error:".$e->getMessage());

            $response = false;
        }
        return $response;
    }

    /**
     * 写入日志
     * @param $logTitle
     * @param string $data
     * @return bool
     */
    public function writeLog($logTitle, $data = '')
    {
        $log = $this->log;

        $log_str = is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE);
        $msg = vsprintf('[%s]: ' . $log_str . PHP_EOL, array(
            $logTitle
        ));
        $encode = mb_detect_encoding($msg, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
        if ($encode != "UTF-8") {
            $msg = mb_convert_encoding($msg, "UTF-8", $encode);
        }
        $log->debug($msg);

        return true;
    }
}
