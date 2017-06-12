<?php

namespace App\Entities;

use App\Entities\ImageMime as ImageMime;

/**
 * Description of ImageProperties
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ImageProperties
{
	private $uri;
	private $width;
	private $height;
	private $mime;
	
	public function __construct($uri, $width, $height, ImageMime $mime)
	{
		$this->uri = $uri;
		$this->width = $width;
		$this->height = $height;
		$this->mime = $mime;
	}
}
