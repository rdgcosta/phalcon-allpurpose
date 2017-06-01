<?php

return new Phalcon\Config([
	'application' => [
		'logsDir'  => $logs_dir = $config->application->logsDir . 'api/'
	],
	'api' => [
		'forceSSL' => false,
		'logRequests' => true,
		'logFile'  => $logs_dir . 'apirequests.log',
		'sessionLifetime' => 604800 //one week
	]
]);
