<?php

namespace PhalconProject\Frontend\Routes;

class Index extends Group
{

	const NAME_PREFIX = 'frontend-index';

	public function initialize()
	{
		$this->setPaths(
			[
				'controller' => 'Index'
			]
		);

		$this->addGet('/', [
			'action' => 'index'
		])->setName(static::NAME_PREFIX . '-index');
	}

}