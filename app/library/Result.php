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
        self::getJson(ResultCode::SUCCESS, $message, $data)->send();
    }

    /**
     * 失败返回
     * @param $code
     * @param $message
     * @param array $data
     */
    public static function error($code = 500, $message = '未知错误', $data = [])
    {
        self::getJson($code, $message, $data)->send();
        exit;
    }

    /**
     * json response
     * @param $code
     * @param $message
     * @param $data
     * @return Response
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
        return $response;
    }
}
