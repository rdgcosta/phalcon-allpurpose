<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
	[
		'PhalconProject' => $config->application->appDir,
	    'TLib'      => $config->application->libDir
	]
);

$loader->register();

return $loader;
