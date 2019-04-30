<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Mvc\Dispatcher;
use App\Library\PhalconBaseVolt;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use App\Library\Common;
use Phalcon\Http\Request;
use Phalcon\Session\Adapter\Redis as RedisSession;
use Phalcon\Logger\Adapter\File as LogFile;
use Phalcon\Logger\Formatter\Line as LogLine;
use Phalcon\Db\Profiler as DbProfiler;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.phtml' => function ($view) {
            $config = $this->getConfig();

//            $volt = new VoltEngine($view, $this);
            $volt = new PhalconBaseVolt($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            $volt->initFunction();

            return $volt;
        },
//        '.phtml' => PhpEngine::class
    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db_acc', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);
    $eventManager = new Manager();
    $profiler = new DbProfiler();
    $eventManager->attach('db', function ($event, $connection) use ($profiler) {
        if($event->getType() == 'beforeQuery'){
            //在sql发送到数据库前启动分析
            $profiler -> startProfile($connection->getSQLStatement());
            $profile = $profiler->getLastProfile();
            $sql = $profile->getSQLStatement();
            $params = $connection->getSqlVariables();
            (is_array($params) && count($params)) && $params = json_encode($params);
            $this->getLog()->info('【db_acc】执行前的sql:'. $sql . PHP_EOL .'参数:' . $params);
        }
        if($event -> getType() == 'afterQuery'){
            //在sql执行完毕后停止分析
            $profiler -> stopProfile();
            //获取分析结果
            $profile = $profiler -> getLastProfile();
            $sql = $profile->getSQLStatement();
            $params = $connection->getSqlVariables();
            (is_array($params) && count($params)) && $params = json_encode($params);
            $executeTime = $profile->getTotalElapsedSeconds();
            //日志记录
            $this->getLog()->info('【db_acc】执行后的sql:'. $sql . PHP_EOL .'参数:' . $params);
            $this->getLog()->info('执行时间:'. $executeTime);
        }
    });
    $connection->setEventsManager($eventManager);

    return $connection;
});
/**
 * 设置多数据库
 */
$di->setShared('db_crm', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database1->adapter;
    $params = [
        'host'     => $config->database1->host,
        'username' => $config->database1->username,
        'password' => $config->database1->password,
        'dbname'   => $config->database1->dbname,
        'charset'  => $config->database1->charset,
    ];

    if ($config->database1->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);
    $eventManager = new Manager();
    $profiler = new DbProfiler();
    $eventManager->attach('db', function ($event, $connection) use ($profiler) {
        if($event->getType() == 'beforeQuery'){
            //在sql发送到数据库前启动分析
            $profiler -> startProfile($connection->getSQLStatement());
            $profile = $profiler->getLastProfile();
            $sql = $profile->getSQLStatement();
            $params = $connection->getSqlVariables();
            (is_array($params) && count($params)) && $params = json_encode($params);
            $this->getLog()->info('【db_crm】执行前的sql:'. $sql . PHP_EOL .'参数:' . $params);
        }
        if($event -> getType() == 'afterQuery'){
            //在sql执行完毕后停止分析
            $profiler -> stopProfile();
            //获取分析结果
            $profile = $profiler->getLastProfile();
            $sql = $profile->getSQLStatement();
            $params = $connection->getSqlVariables();
            (is_array($params) && count($params)) && $params = json_encode($params);
            $executeTime = $profile->getTotalElapsedSeconds();
            //日志记录
            $this->getLog()->info('【db_crm】执行后的sql:'. $sql . PHP_EOL .'参数:' . $params);
            $this->getLog()->info('执行时间:'. $executeTime);
        }
    });
    $connection->setEventsManager($eventManager);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    // 注释文件类型session
//    $session = new SessionAdapter();

    // 启用redis类型的session
    $config = $this->getConfig();
    $session = new RedisSession([
        'host' => $config->redis->host,
        'port' => $config->redis->port,
        'password' => $config->redis->password,
    ]);

    $session->start();

    return $session;
});

/**
 * 调度控制器 并在其中植入事件管理器
 */
$di->set('dispatcher', function () {
    $eventManager = new Manager();

    $request = new Request();
    $url = $request->getURI();

    $eventManager->attach('dispatch:beforeException', function (Event $event, $dispatcher, Exception $exception) use ($url) {
        if ($exception instanceof DispatchException) {
            if( Common::startsWith($url, '/admin')){
                $dispatcher->forward([
                    'controller' => 'admin',
                    'action' => 'index',
                    'params' => ['_from' => $url],
                ]);
                return false;
            }
        }
    });

    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventManager);
    $dispatcher->setDefaultNamespace('App\Controllers');
    return $dispatcher;
});

/**
 * 注册redis服务到服务容器
 */
$di->setShared('redis', function () {
    $config = $this->getConfig();
    $redis = new Redis();
    $redis->connect($config->redis->host, $config->redis->port);
    $redis->auth($config->redis->password);
    $redis->setOption(Redis::OPT_PREFIX, 'accounting-system:');
    return $redis;
});

/**
 * 注册日志服务
 */
$di->setShared('log', function() {
    $config = $this->getConfig();
    if(!is_dir($config->application->logDir)){
        mkdir($config->application->logDir,'0755',true);
    }
    $log = new LogFile($config->application->logDir. date('Y-m-d') . '.log');
    $formatter = new LogLine("[%date%][".$GLOBALS['ssid']."][%type%] %message%");
    $formatter->setDateFormat("Y/m/d H:i:s");
    $log->setFormatter($formatter);
    return $log;
});
