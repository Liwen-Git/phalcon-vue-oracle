<?php
use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    /**
     * composer autoload
     */
    include BASE_PATH . '/vendor/autoload.php';

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    spl_autoload_register(function ($class) {
        if ($class) {
            $file = str_replace('\\', '/', $class);
            $file .= '.php';

            if (!file_exists($file)) {
                $classParts = explode("/", $file);
                $rebuildClass = '';
                foreach ($classParts as $part) {
                    $part = ucfirst($part);
                    $rebuildClass .= $part . "/";
                }
                $rebuildClass = rtrim($rebuildClass, "/");
                $file = BASE_PATH . '/vendor/phalcon/incubator/Library/' . $rebuildClass;
                include_once $file;
            }
        }
    });

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
