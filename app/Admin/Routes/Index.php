<?php

namespace PhalconProject\Admin\Routes;

class Index extends Group
{

	const NAME_PREFIX = 'admin-index';

	public function initialize()
	{
		$this->setPaths(
			[
				'controller' => 'Index'
			]
		);

		$this->setPrefix('');

		$this->addGet('', [
			'action' => 'index'
		])->setName(static::NAME_PREFIX . '-index');
	}

}