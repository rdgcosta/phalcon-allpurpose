<?php

namespace PhalconProject\Api\Validations;

use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class LoginValidation extends Validation
{

	public function validate($data = null, $entity = null)
	{
		$keys = [];
		if(is_array($data)) $keys = array_keys($data);

		if(array_key_exists('email', $data))
		{
			$this->add(
				'email',
				new PresenceOf(['cancelOnFail' => true])
			);
			$this->add(
				'email',
				new Email()
			);
		}

		if(array_key_exists('password', $data))
		{
			$this->add(
				'password',
				new PresenceOf()
			);
		}

		return parent::validate($data, $entity);
	}

}
