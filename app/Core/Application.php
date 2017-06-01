<?php

namespace PhalconProject\Core;

use Phalcon\Mvc\Application as PhalconApplication;

class Application extends PhalconApplication
{

	public $modules = [];

	public function __construct($dependencyInjector)
	{
		global $modules;

		parent::__construct($dependencyInjector);

		$this->modules = $modules;

		$this->registerModules($modules);
	}

}
