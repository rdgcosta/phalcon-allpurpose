<?php

use Phalcon\Mvc\Router;

$module_router = new Router(false);
$router = new Router(false);
$router->removeExtraSlashes(true);

foreach($modules as $module=>$paths)
{
	//Add module prefix to modules_router
	$module_router->add("{$paths['prefix']}/(.*)", [
		'module' => $module
	]);

	//Load module routes
	$module_routes = $config->application->appDir . ucfirst($module) . '/Config/routes.php';
	if(file_exists($module_routes)) require_once($module_routes);
}

$module_router->handle();
$current_module = $module_router->getModuleName();
$application->setDefaultModule($current_module);

$router->notFound([
	'controller' => 'errors',
    'action'     => 'show404'
]);

return $router;
