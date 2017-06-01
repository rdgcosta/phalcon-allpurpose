<?php

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);
set_time_limit(600);

define('APP_PATH', __DIR__);

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

if(is_readable(APP_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php')) 
{
	require_once APP_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
}

/**
 * Read services
 */
include APP_PATH . '/config/services.php';
