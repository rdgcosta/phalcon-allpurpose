<?php

namespace PhalconProject\Api\Exception;

class QueryStringException extends APIException
{

	public function __construct($detail = '', $errors = null, $code = 0, \Exception $previous = null)
	{
		if(empty($detail))
		{
			$detail = 'Invalid query string parameter(s). Please fix the errors and try again.';
		}

		parent::__construct('invalid-query-string', 'Invalid Query String', $detail, $errors, $code, $previous);
	}

}