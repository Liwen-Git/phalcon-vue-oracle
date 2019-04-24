<?php


namespace App\Library;


use Phalcon\Http\Response;

class Result
{
    /**
     * 请求成功返回
     * @param string $message
     * @param array $data
     */
    public static function success($message = '请求成功', $data = [])
    {
        if (is_array($message) || $message instanceof \ArrayAccess) {
            $data = $message;
            $message = '请求成功';
        }
        return self::getJson(ResultCode::SUCCESS, $message, $data);
    }

    /**
     * 失败返回
     * @param $code
     * @param $message
     * @param array $data
     */
    public static function error($code, $message, $data = [])
    {
        return self::getJson($code, $message, $data);
    }

    /**
     * json response
     * @param $code
     * @param $message
     * @param $data
     */
    public static function getJson($code, $message, $data)
    {
        $response = new Response();
        $response->setJsonContent([
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time(),
        ]);
        return $response->send();
    }
}
