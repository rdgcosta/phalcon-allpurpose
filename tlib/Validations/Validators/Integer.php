<?php

namespace TLib\Validations\Validators;

use Phalcon\Validation\Validator;
use Phalcon\Validation as PhalconValidation;
use Phalcon\Validation\Message;

class Integer extends Validator
{

	/**
	 * Executes the validation
	 *
	 * @param PhalconValidation $validation
	 * @param string $attribute
	 * @return boolean
	 */
	public function validate(PhalconValidation $validation, $attribute)
	{
		$value = $validation->getValue($attribute);

		if(!filter_var($value, FILTER_VALIDATE_INT))
		{
			$message = $this->getOption('message');
			if(!$message) $message = 'Field ' . $attribute . ' does not have a valid integer format';

			$validation->appendMessage(new Message($message, $attribute, 'Integer'));

			return false;
		}

		return true;
	}

}
