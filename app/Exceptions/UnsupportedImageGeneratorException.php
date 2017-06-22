<?php

namespace App\Exceptions;

/**
 * Description of UnsupportedImageGeneratorException
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class UnsupportedImageGeneratorException extends \Exception
{
	public function __construct($message, $code = 0, Exception $previous = null)
	{
        parent::__construct($message, $code, $previous);
    }
}
