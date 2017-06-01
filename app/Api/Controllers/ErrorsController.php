<?php

namespace PhalconProject\Api\Controllers;

use Phalcon\Http\Response;
use PhalconProject\Api\Exception\APIException;

class ErrorsController extends Controller
{

	/**
	 * Bad request
	 *
	 * The 400 (Bad Request) status code indicates that the server cannot or
	 * will not process the request due to something that is perceived to be
	 * a client error (e.g., malformed request syntax, invalid request
	 * message framing, or deceptive request routing).
	 *
	 * @param APIException $exception
	 * @param int $code [optional]
	 *
	 * @return Response
	 */
	public function exceptionAction(APIException $exception, $code = 400)
	{
		$error = [
			'id' => $exception->getId(),
			'title' => $exception->getTitle(),
			'detail' => $exception->getDetail()
		];

		$errors = null;
		if(!is_null($exception->getErrors())) $errors = $exception->getErrors();

		return $this->createErrorsResponse(
			$error,
			$errors,
			$code
		);
	}

	/**
	 * Forbidden
	 *
	 * The 403 (Forbidden) status code indicates that the server understood
	 * the request but refuses to authorize it.  A server that wishes to
	 * make public why the request has been forbidden can describe that
	 * reason in the response payload (if any).
	 */
	public function show403Action()
	{
		return $this->createErrorResponse(
			[
				'id' => 'forbidden',
				'title' => 'Forbidden',
				'detail' => 'You are not authorized to access this resource.'
			],
			403
		);
	}

	/**
	 * The 404 (Not Found) status code indicates that the origin server did
	 * not find a current representation for the target resource or is not
	 * willing to disclose that one exists.  A 404 status code does not
	 * indicate whether this lack of representation is temporary or
	 * permanent; the 410 (Gone) status code is preferred over 404 if the
	 * origin server knows, presumably through some configurable means, that
	 * the condition is likely to be permanent.
	 *
	 * @return Response
	 */
	public function show404Action()
	{
		return $this->createErrorResponse(
			[
				'id' => 'not-found',
				'title' => 'Not Found',
				'detail' => 'The resource you are trying to access was not found on this server.'
			],
			404
		);
	}

	/**
	 * Internal Server Error
	 *
	 * The 500 (Internal Server Error) status code indicates that the server
	 * encountered an unexpected condition that prevented it from fulfilling
	 * the request.
	 */
	public function show500Action()
	{
		return $this->createErrorResponse(
			[
				'id' => 'internal-server-error',
				'title' => 'Internal Server Error',
				'detail' => 'An unexpected error occurred in our server, please try again later.'
			],
			500
		);
	}

}
