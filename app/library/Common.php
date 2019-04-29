<?php


namespace App\Library;

use Phalcon\Exception;

class Common
{
    /**
     * vue mix函数
     * @param $path
     * @param string $manifestDirectory
     * @return string
     * @throws Exception
     */
    public static function mix($path, $manifestDirectory = '')
    {
        static $manifests = [];

        if (! self::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if ($manifestDirectory && ! self::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        $manifestPath = BASE_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . ltrim($manifestDirectory.'/mix-manifest.json', DIRECTORY_SEPARATOR);

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
            throw new Exception("Unable to locate Mix file: {$path}.");

        }

        return $manifestDirectory.$manifest[$path];
    }

    /**
     * 判断开头是否是不是存在
     * @param $haystack
     * @param $needles
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 数组 转 树形结构
     * @param $list
     * @param int $root
     * @param string $idName
     * @param string $pidName
     * @param string $child
     * @return array
     */
    public static function listToTree($list, $root = 0, $idName = 'id', $pidName = 'pid', $child = 'sub')
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$idName]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pidName];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 对象转数组
     * @param $object
     * @return array|void
     */
    public static function objectToArray($object)
    {
        $array=(array)$object;
        foreach($array as $k=>$v){
            if( gettype($v)=='resource' ) {
                return;
            }
            if( gettype($v)=='object' || gettype($v)=='array' ) {
                $array[$k]=(array)self::objectToArray($v);
            }
        }
        return $array;
    }
}
