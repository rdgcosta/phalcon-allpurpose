<?php

use TLib\Utils\Stringify;
use Phalcon\Cache\Frontend\Data as FrontendCache;
use Phalcon\Cache\Backend\Memory as BackendCache;
use Phalcon\Config;
use Phalcon\Db\Adapter as DbAdapter;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\File as Logger;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

/**
 * @var \Phalcon\Di\FactoryDefault $di
 */

/**
 * Shared configuration service
 */
$di->setShared('config', function ()
{
	/** @var Config $config */
	$config = include APP_PATH . '/config/config.php';

	if(is_readable($config->application->configDir . 'config.env.php'))
	{
		$override = include $config->application->configDir . 'config.env.php';
		$config->merge($override);
	}

	return $config;
});

/**
 * Shared loader service
 */
$di->setShared('loader', function ()
{
	$config = $this->getConfig();

    /**
     * Include Autoloader
     */
	$loader = include APP_PATH . '/config/loader.php';

    return $loader;
});

/**
 * Set router
 */
$di->setShared('router', function ()
{
	global $modules, $application;

	$config = $this->getConfig();

	/**
	 * Include Routes
	 */
	$router = include APP_PATH . '/config/routes.php';

	return $router;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function ()
{
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function ()
{
	$config = $this->getConfig();

	$view = new View();
	$view->registerEngines(
		[
			'.volt' => function ($view, $di) use ($config)
			{
				$volt = new VoltEngine($view, $di);

				$volt->setOptions([
					'compiledPath' => $config->application->cacheDir,
					'compiledSeparator' => '_'
				]);

				return $volt;
			}
		]
	);
	$view->setRenderLevel(View::LEVEL_ACTION_VIEW);

	return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function ()
{
    $config = $this->getConfig();

	$db_config = $config->database->toArray();
	$class = 'Phalcon\Db\Adapter\Pdo\\' . $db_config['adapter'];
	$connection = new $class($db_config);

	if(!empty($db_config['logQueries']))
	{
		$logger = new Logger(
			$config->application->logsDir . $db_config['logFile'],
			['mode' => 'a']
		);

		$em = new EventsManager();

		$stringify = new Stringify(['max_array_elements' => 20]);
		$em->attach('db', function (Event $event, DbAdapter $connection) use ($db_config, $logger, $stringify)
		{
			if($event->getType() == 'beforeQuery')
			{
				$logger->info(
					"\nSQL: " . $connection->getSQLStatement() .
					"\nPARAMS: " . $stringify->variable($connection->getSqlVariables()) . "\n" .
					($db_config['logTrace'] ? "TRACE:\n" . $stringify->backtrace() . "\n" : '')
				);
			}
		});

		$connection->setEventsManager($em);
	}

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function ()
{
    return new MetaDataAdapter();
});

/*
 * Set the models cache service
 */
$di->set('modelsCache', function ()
{
	/*
	 * Cache data for 30 minutes by default
	 */
	$frontCache = new FrontendCache(
		['lifetime' => 1800]
	);

	$cache = new BackendCache($frontCache);

	return $cache;
});

$di->set('log', function ()
{
	$config = $this->getConfig();

	$logger = new Logger(
		$config->application->logsDir . 'default.log',
		['mode' => 'a']
	);

	return $logger;
});

/**
 * We register the events manager
 */
$di->setShared('dispatcher', function ()
{
	$em = new EventsManager();

	$dispatcher = new Dispatcher();
	$dispatcher->setEventsManager($em);

	return $dispatcher;
});
