<?php

namespace PhalconProject\Admin\Controllers;

use Phalcon\Http\Response;

class ErrorsController extends Controller
{

	public function show404Action()
	{
		return $this->view(
			'errors/404',
			[],
			404
		);
	}

	public function show500Action()
	{
		return $this->view(
			'errors/500',
			[],
			500
		);
	}

}
