<?php

namespace App\Entities;

/**
 * Description of RequestParams
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class RequestParams
{
	private $params;
	
	public function __construct()
	{
		$this->params = $_REQUEST;
	}
	
	public function __get($name)
	{
		return isset($this->params[$name])?$this->params[$name]:null;
	}
}
