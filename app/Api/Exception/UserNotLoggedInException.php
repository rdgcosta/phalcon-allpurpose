<?php

namespace PhalconProject\Api\Exception;

class UserNotLoggedInException extends APIException
{

	public function __construct($detail = '', $code = 0, \Exception $previous = null)
	{
		if(empty($detail))
		{
			$detail = 'A logged in user is required to perform this action.';
		}

		parent::__construct('user-logged-in-required', 'User Logged In Required', $detail, null, $code, $previous);
	}

}
