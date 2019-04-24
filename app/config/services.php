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
        'charset'  => $config->database1->charset
    ];

    if ($config->database1->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

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
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->set('dispatcher', function () {
    $eventManager = new Manager();
    $eventManager->attach('dispatch:beforeException', function (Event $event, $dispatcher, Exception $exception) {
        if ($exception instanceof DispatchException) {
            $request = new Request();
            $url = $request->getURI();
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
