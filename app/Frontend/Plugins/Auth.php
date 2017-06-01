<?php

namespace PhalconProject\Frontend\Plugins;

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
		 * First key is controller, second is the action
		 */
		$protected_resources = [
			'checkout' => ['index'],
		];

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		if(
			isset($protected_resources[$controller]) &&
			in_array($action, $protected_resources[$controller])
		) {
			if(!$this->auth->check())
			{
				$this->response->redirect(['for' => 'frontend-index-login']);
				return false;
			}
		}

		return true;
	}

}
