<?php

namespace PhalconProject\Admin\Controllers;

class IndexController extends Controller
{

	public function indexAction()
	{
	    return $this->view('index/index');
	}

}
