<?php

namespace App\Exceptions;

/**
 * Description of InvalidImageBaseException
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class InvalidImageBaseException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
        parent::__construct($message, $code, $previous);
    }
}
