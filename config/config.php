<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

global $modules;
$modules = [
	'frontend' => [
		'className' => 'PhalconProject\Frontend\Module',
	    'path' => __DIR__ . '/../app/Frontend/Module.php',
		'prefix' => ''
	],
	'admin' => [
		'className' => 'PhalconProject\Admin\Module',
		'path' => __DIR__ . '/../app/Admin/Module.php',
		'prefix' => '/admin'
	],
	'api' => [
		'className' => 'PhalconProject\Api\Module',
		'path' => __DIR__ . '/../app/Api/Module.php',
	    'prefix' => '/api'
	]
];

$folder = '/';
if(!empty($_SERVER['DOCUMENT_ROOT']))
{
	$folder = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath('.'));
	if(DIRECTORY_SEPARATOR != '/') { $folder = str_replace(DIRECTORY_SEPARATOR, '/', $folder); }
	if($folder != '/') { $folder .= '/'; }
}

return new Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'phalcon',
        'charset'     => 'utf8',
        'logQueries'  => true,
        'logFile'     => 'queries.log',
        'logTrace'    => false
    ],
    'application' => [
	    'appDir'      => APP_PATH . '/app/',
	    'migrationsDir' => realpath(__DIR__.'/../database/migrations'),
	    'libDir'      => APP_PATH . '/tlib/',
	    'configDir'   => APP_PATH . '/config/',
        'cacheDir'    => APP_PATH . '/cache/',
	    'logsDir'     => $logs_dir = APP_PATH . '/logs/',
        'baseUri'     => $base_uri = $folder,
		'forceSSL'    => false
    ],
	'api' => [
		'baseUri'          => 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' .
			(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') .
			$base_uri . 'api/v1',
		'token'            => 'TATKDD94315970267CE8A8BB9D94875618A2FFBD53D2',
		'timeout'          => 10,
		'logCalls'         => true,
		'logFile'          => $logs_dir . 'apicalls.log',
		'logLongCalls'     => true,
		'longCallsTimeout' => 4,
		'longCallsLogFile' => $logs_dir . 'apilongcalls.log'
	]
]);
