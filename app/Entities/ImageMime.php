<?php

namespace App\Entities;

/**
 * Description of ImageMime
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ImageMime
{
	private $mime;
	
	public function set_mime($mime_string)
	{
		$this->mime = $mime_string;
	}
	
	public function get_extension()
	{
		return substr($this->mime, strpos($this->mime, '/') + 1);
	}
	
	public function __toString()
	{
		return $this->mime;
	}
}
