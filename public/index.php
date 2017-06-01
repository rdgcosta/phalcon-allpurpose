<?php

use Phalcon\Di\FactoryDefault;
use PhalconProject\Core\Application;

require_once(__DIR__ . '/../base.php');

/**
 * @var FactoryDefault $di
 * Call the autoloader service.  We don't need to keep the results.
 */
$di->getLoader();

/**
 * Handle the request
 */
$application = new Application($di);

try {
	//Header
	$response = $application->handle();
	$response->send();
} catch(Exception $e) {
	header('Content-Type: text/plain; charset=UTF-8');
	echo $e->getMessage(), "\n";
	echo $e->getTraceAsString();
	exit(0);
}

