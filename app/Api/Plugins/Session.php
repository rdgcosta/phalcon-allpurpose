<?php

namespace PhalconProject\Api\Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Session\Adapter\Files as PhalconSession;

class Session extends Plugin
{

	public static $started = false;

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$headers = $this->request->getHeaders();
		$query = $this->request->getQuery();

		$session_id = null;
		if(isset($headers['X-Tsession'])) $session_id = $headers['X-Tsession'];
		if(isset($query['tsession'])) $session_id = $query['tsession'];

		if($session_id)
		{
			$save_path = ini_get('session.save_path');
			$file = $save_path . '/sess_' . $session_id;
			if(file_exists($file))
			{
				static::$started = true;
				$this->di->setShared('session', function () use ($session_id)
				{
					ini_set('session.use_cookies', 0);
					ini_set('session.use_only_cookies', 0);
					ini_set('session.use_trans_sid', 1);
					ini_set('session.cache_limiter', '');

					$session = new PhalconSession();
					$session->setId($session_id);
					$session->start();

					return $session;
				});
			}
		}

		if(!static::$started)
		{
			$this->di->setShared('session', function ()
			{
				return null;
			});
		}

		return true;
	}

	public function afterDispatchLoop(Event $event, Dispatcher $dispatcher)
	{
		$this->response->setHeader('TSession', static::$started ? 1 : 0);
	}

}
