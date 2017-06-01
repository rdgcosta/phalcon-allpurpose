<?php

use Phalcon\Config;
use Phalcon\Di\FactoryDefault;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use PhalconProject\Frontend\Http\API\API;

/** @var FactoryDefault $di */
/** @var Config $config */

/** @var View $view */
$view = $di->getShared('view');
$view->setViewsDir($config->application->viewsDir);

/** @var Dispatcher $dispatcher */
$dispatcher = $di->getShared('dispatcher');
$dispatcher->setDefaultNamespace('PhalconProject\Frontend\Controllers');


/**
 * Set up the session service
 */
$di->setShared('session', function ()
{
	$session = new Session();
	$session->setName('phalconproject');
	$session->start();

	return $session;
});

/**
 * Set up the flash service
 */
$di->set('flash', function ()
{
	return new FlashDirect();
});

/**
 * Set up the flash session service
 */
$di->set('flashSession', function ()
{
	return new FlashSession();
});

/**
 * Setup for api calls
 */
$di->setShared('api', function () use ($di)
{
	$config = $di->getConfig();

	$api = new API($config->api->toArray());

	return $api;
});

/**
 * Set up the auth component
 */

$em = $dispatcher->getEventsManager();

/**
 * Checks if a user is logged and redirects to login page if not
 */
//$em->attach('dispatch:beforeExecuteRoute', new AuthPlugin());