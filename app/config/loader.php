<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir,
    ]
);

$loader->registerNamespaces([
    'App\Controllers' => APP_PATH . '/controllers/',
    'App\Models'      => APP_PATH . '/models/',
    'App\Library'     => APP_PATH . '/library/',
    'App\Service'     => APP_PATH . '/service/',
    'Phalcon'         => BASE_PATH .'/vendor/phalcon/incubator/Library/Phalcon/'
]);

$loader->register();
