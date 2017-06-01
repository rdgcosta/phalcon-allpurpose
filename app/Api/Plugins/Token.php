<?php

namespace PhalconProject\Api\Plugins;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use PhalconProject\Core\Models\Action;
use PhalconProject\Core\Models\Api;
use PhalconProject\Core\Models\RoleAction;

class Token extends Plugin
{

	/** @var Api $api  */
	public static $api = null;

	/**
	 * Returns an existing or new access control list
	 *
	 * @param Api $api
	 *
	 * @returns AclList
	 */
	protected function _getAcl(Api $api = null)
	{
		/**
		 * @todo Colocar sessão por API de token, colocar campo "refresh" na API, quando der true ele carrega denovo os roles
		 */
		$allowed_resources = [
			'errors' => ['exception', 'show400', 'show401', 'show403', 'show404', 'show500']
		];

		if($api)
		{
			$ids = []; $admin = false;
			/** @var Role $role */
			foreach($api->Roles as $role)
			{
				if($role->admin) $admin = true;
				$ids[] = (int)$role->id;
			}

			$query = Action::query();
			if(!$admin)
			{
				$action_model = Action::class;
				$query->join(RoleAction::class, "ra.action_id = $action_model.id", 'ra')
					->where('ra.role_id IN (' . implode(',', $ids) . ')');
			}

			/** @var Action[] $actions */
			$actions = $query->execute();
			foreach($actions as $action)
			{
				list($controller, $action) = explode('@', $action->key);
				if(!isset($allowed_resources[$controller])) {
					$allowed_resources[$controller] = [];
				}

				$allowed_resources[$controller][] = $action;
			}

		}

		$acl = new AclList();
		$acl->setDefaultAction(Acl::DENY);

		$roles = [
			new Role('Tokens'),
			new Role('Guests')
		];

		foreach($roles as $role)
		{
			$acl->addRole($role);
		}

		foreach($allowed_resources as $controller=>$actions)
		{
			$acl->addResource(new Resource($controller), $actions);
			foreach($roles as $role)
			{
				$acl->allow($role->getName(), $controller, $actions);
			}
		}

		return $acl;
	}

	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
	{
		$headers = $this->request->getHeaders();
		$query = $this->request->getQuery();

		$token = null;
		if(isset($headers['X-Ttoken'])) $token = $headers['X-Ttoken'];
		if(isset($query['ttoken'])) $token = $query['ttoken'];

		/** @var Api $api */
		$api = Api::findBy('token', $token, true);
		if($api === false) $api = null;
		if($api)
		{
			static::$api = $api;
			$role = 'Tokens';
		}
		else
		{
			$role = 'Guests';
		}

		$acl = $this->_getAcl($api);

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$allowed = $acl->isAllowed($role, $controller, $action);
		if($allowed != Acl::ALLOW)
		{
			$dispatcher->forward([
				'controller' => 'errors',
				'action' => 'show403'
			]);
			return false;
		}

		return true;
	}

}
