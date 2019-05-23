<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Oracle',
        'host'        => '192.168.7.81',
        'username'    => 'acc_user',
        'password'    => 'account##234',
        'port'        => '1521',
        'dbname'      => '192.168.7.81:1521/account',
        'charset'     => 'utf8',
        'service_name'=> 'account',
    ],
    'database1' => [
        'adapter'     => 'Oracle',
        'host'        => '192.168.7.81',
        'username'    => 'crm_user',
        'password'    => 'Vb_gh879C23',
        'port'        => '1521',
        'dbname'      => '192.168.7.81:1521/account',
        'charset'     => 'utf8',
        'service_name'=> 'account',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'logDir'         => BASE_PATH . '/cache/log/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],
    'redis' => [
        'host' => '192.168.7.80',
        'port' => '19000',
        'password' => '',
    ],
    /**
     * TfbApi Environment [开发=>dev;测试=>test;生产=>product]
     */
    'isDebug' => 'test',
]);
