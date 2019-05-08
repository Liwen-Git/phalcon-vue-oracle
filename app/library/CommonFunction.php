<?php

/**
 * 判断数组中是否存在某个键 有则返回对应的值
 * @param $array
 * @param $key
 * @param null $default
 * @return mixed|null
 */
function array_get($array, $key, $default = null)
{
    if (is_null($key)) {
        return $array;
    }

    if (isset($array[$key])) {
        return $array[$key];
    }

    foreach (explode('.', $key) as $segment) {
        if (! is_array($array) || ! array_key_exists($segment, $array)) {
            return $default;
        }

        $array = $array[$segment];
    }
    return $array;
}

/**
 * @param string $str 需要替换的字符串
 * @param int $start_len
 * @param int $end_len
 * @return string 替换后的字符串
 */
function substr_cut($str, $start_len = 4, $end_len = 3){
    $rep_str = "";
    if (strlen($str) < ($start_len + $end_len+1)) {
        return $str;
    }
    $count = strlen($str) - $start_len - $end_len;
    for ($i = 0; $i < $count; $i++) {
        $rep_str.="*";
    }
    return preg_replace('/(\d{' . $start_len . '})\d+(\d{' . $end_len . '})/', '${1}' . $rep_str . '${2}', $str);
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv = false) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
