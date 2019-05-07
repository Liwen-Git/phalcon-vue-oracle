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
