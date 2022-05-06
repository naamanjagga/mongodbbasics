<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;



// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once BASE_PATH . '/vendor/autoload.php';
// $nw=  BASE_PATH . '/vendor/autoload.php';
// echo $nw; die;

$di = new FactoryDefault();

include APP_PATH.'/config/router.php';

include APP_PATH.'/config/services.php';

$config = $di->getConfig();

include APP_PATH.'/config/loader.php';

$application = new Application($di);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();

   
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}