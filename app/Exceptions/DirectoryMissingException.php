<?php

namespace App\Exceptions;

/**
 * Description of DirectoryMissingException
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class DirectoryMissingException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
        parent::__construct($message, $code, $previous);
    }
}
