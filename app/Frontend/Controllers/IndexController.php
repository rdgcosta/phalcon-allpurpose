<?php

namespace PhalconProject\Frontend\Controllers;

class IndexController extends Controller
{

	public function indexAction()
	{
		$this->view(
			'index/index',
			[
				'var1' => 'value1'
			],
			200,
			[]
		);
	}

}