<?php

namespace PhalconProject\Api\Controllers;

use Phalcon\Http\Response;
use PhalconProject\Api\Exception\SessionException;
use PhalconProject\Api\Exception\UserNotLoggedInException;
use PhalconProject\Api\Security\Auth;
use PhalconProject\Core\Controllers\Controller as CoreController;

/**
 * @property Auth $auth
 */
class Controller extends CoreController
{

	/** @var int */
	public $initTime = null;

	/** @var Response */
	public $response = null;

	/**
	 * Function
	 */
	public function initialize()
	{
		$this->initTime = microtime(true);
	}

	/**
	 * @param array $data The data to be set.
	 * @param int $status [optional] Response status code
	 * @param array $meta [optional] Extra meta data about the response
	 * @param array $headers [optional] Extra headers to be sent
	 *
	 * @return Response
	 */
	public function createResponse($data, $status = 200, $headers = [], $meta = [])
	{
		$main = [
			'meta' => $this->_getMeta('data', $meta),
			'data' => $data
		];

		return $this->createJsonResponse($main, $status, $headers);
	}

	/**
	 * @param array $error A single error object.
	 * @param array $errors [optional] A single error object or many.
	 * @param int $status [optional] Error status code
	 * @param array $headers [optional] Extra headers to be sent
	 *
	 * @return Response
	 */
	public function createErrorsResponse($error, $errors = [], $status = 400, $headers = [])
	{
		if(!isset($errors[0])) $errors = empty($errors) ? [] : [$errors];

		$main = [
			'meta' => $this->_getMeta('error'),
			'error' => $error,
			'errors' => $errors
		];

		return $this->createJsonResponse($main, $status, $headers);
	}

	/**
	 * @param array $error A single error object.
	 * @param int $status [optional] Error status code
	 * @param array $headers [optional] Extra headers to be sent
	 *
	 * @return Response
	 */
	public function createErrorResponse($error, $status = 400, $headers = [])
	{
		return $this->createErrorsResponse($error, [], $status, $headers);
	}

	protected function _getMeta($type = null, $extraMeta = [])
	{
		$time_taken = round((microtime(true) - $this->initTime) * 1000, 2);

		$request = $this->request->getMethod() . ' ' . $this->request->getURI();

		return array_merge([
			'type' => $type,
			'time' => $time_taken,
			'request' => $request
		], $extraMeta);
	}

	protected function _requireSession($detail = null)
	{
		if(!$this->session)
		{
			$this->dispatcher->forward([
				'controller' => 'errors',
				'action' => 'exception',
				'params' => [new SessionException($detail)]
			]);
			return false;
		}

		return true;
	}

	protected function _requireUserLoggedIn($detail = null)
	{
		if(!$this->_requireSession()) return false;

		if(!$this->auth->check())
		{
			$this->dispatcher->forward([
				'controller' => 'errors',
				'action' => 'exception',
				'params' => [new UserNotLoggedInException($detail)]
			]);
			return false;
		}

		return true;
	}


}
