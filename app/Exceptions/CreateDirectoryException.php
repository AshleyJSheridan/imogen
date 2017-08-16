<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Exceptions;

/**
 * Description of CreateDirectoryException
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CreateDirectoryException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
        parent::__construct($message, $code, $previous);
    }
}
