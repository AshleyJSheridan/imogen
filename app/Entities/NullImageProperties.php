<?php

namespace App\Entities;

use App\Entities\iImageProperties as iImageProperties;

/**
 * Description of NullImageProperties
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class NullImageProperties implements iImageProperties
{
	private $uri;
	private $width;
	private $height;
	private $mime;
	
	public function set_uri($uri)
	{
		$this->uri = $uri;
	}
	
	public function set_dimensions($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}
	
	public function get_width()
	{
		return $this->width;
	}
	
	public function get_height()
	{
		return $this->height;
	}
	
	public function set_mime(ImageMime $mime)
	{
		$this->mime = $mime;
	}
	
	public function get_mime()
	{
		return $this->mime;
	}
	
	public function get_mime_string()
	{
		return (string)$this->mime;
	}
}
