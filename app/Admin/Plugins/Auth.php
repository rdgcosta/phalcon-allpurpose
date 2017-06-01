<?php

namespace PhalconProject\Admin\Plugins;

use Phalcon\Acl;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class Auth extends Plugin
{

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		/**
		 * All resources that are not protected by user login
		 */
		$unprotected_resources = [
			'index' => ['login', 'logout'],
		];

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		if(
			!isset($unprotected_resources[$controller]) ||
			!in_array($action, $unprotected_resources[$controller])
		) {
			if(!$this->auth->check())
			{
				$this->response->redirect(['for' => 'admin-index-login']);
				return false;
			}
		}

		return true;
	}

}
