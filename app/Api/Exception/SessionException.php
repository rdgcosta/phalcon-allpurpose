<?php

namespace PhalconProject\Api\Exception;

class SessionException extends APIException
{

	public function __construct($detail = '', $code = 0, \Exception $previous = null)
	{
		if(empty($detail))
		{
			$detail = 'A valid session is required to perform this action. ' .
				'Please generate one and pass along it\'s id as a header ' .
				'(X-SMCSession) or query string (smcsession)';
		}

		parent::__construct('session-required', 'Session Required', $detail, null, $code, $previous);
	}

}
