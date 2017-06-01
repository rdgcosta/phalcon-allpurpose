<?php

use Phalcon\Config;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use PhalconProject\Api\Plugins\Exception as ExceptionPlugin;
use PhalconProject\Api\Plugins\Session as SessionPlugin;
use PhalconProject\Api\Plugins\Token as TokenPlugin;
use PhalconProject\Api\Plugins\Log as LogPlugin;

/**
 * @var \Phalcon\Di\FactoryDefault $di
 */

$di->getShared('view')->disable();

/**
 * @var Dispatcher $dispatcher
 */
$dispatcher = $di->getShared('dispatcher');
$dispatcher->setDefaultNamespace('PhalconProject\Api\Controllers');

$em = $dispatcher->getEventsManager();

/**
 * Check if the API token is allowed to access certain action using the TokenPlugin
 */
$em->attach('dispatch', new SessionPlugin());

/**
 * Check if the API token is allowed to access certain action using the TokenPlugin
 */
$em->attach('dispatch:beforeExecuteRoute', new TokenPlugin());

if($config->api->logRequests)
{
	$em->attach('dispatch:afterDispatchLoop', new LogPlugin());
}