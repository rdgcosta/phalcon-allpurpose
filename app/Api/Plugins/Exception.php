<?php

namespace PhalconProject\Api\Plugins;

use Exception as PHPException;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\User\Plugin;
use PhalconProject\Api\Exception\APIException;

/**
 * NotFoundPlugin
 *
 * Handles not-found controller/actions
 */
class Exception extends Plugin
{

	/**
	 * Triggered before the dispatcher throws any exception
	 *
	 * @param Event $event
	 * @param MvcDispatcher $dispatcher
	 * @param PHPException $exception
	 *
	 * @return boolean
	 */
	public function beforeException(Event $event, MvcDispatcher $dispatcher, PHPException $exception)
	{
		error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

		if($exception instanceof APIException)
		{
			$dispatcher->forward([
				'controller' => 'errors',
				'action'     => 'exception',
				'params' => [$exception]
			]);
			return false;
		}
		elseif($exception instanceof DispatcherException)
		{
			switch($exception->getCode())
			{
				case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
				case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
					$dispatcher->forward([
						'controller' => 'errors',
						'action'     => 'show404'
					]);
					return false;
			}
		}

		$dispatcher->forward([
			'controller' => 'errors',
			'action'     => 'show500'
		]);
		return false;
	}

}
