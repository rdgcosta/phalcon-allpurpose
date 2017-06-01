<?php

namespace PhalconProject\Api\Exception;

use Exception;

class APIException extends Exception
{

	protected $_id = null;
	protected $_title = null;
	protected $_detail = null;
	protected $_errors = null;

	public function __construct($id, $title, $detail = '', $errors = null, $code = 0, \Exception $previous = null)
	{
		parent::__construct($detail, $code, $previous);

		$this->_id = $id;
		$this->_title = $title;
		$this->_detail = $detail;
		$this->_errors = $errors;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getTitle()
	{
		return $this->_title;
	}

	public function getDetail()
	{
		return $this->_detail;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

}
