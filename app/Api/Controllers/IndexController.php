<?php

namespace PhalconProject\Api\Controllers;

class IndexController extends Controller
{

	public function helloAction()
	{
		return $this->createResponse(
			[
				'id' => 'hello-world',
				'title' => 'Hello World',
				'detail' => 'This is one example of route'
			]
		);
	}

}
