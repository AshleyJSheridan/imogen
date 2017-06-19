<?php

namespace App\Entities;

use App\Entities\ImageMime as ImageMime;
use App\Entities\ColourList as ColourList;

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
	private $colours;
	
	public function __construct(ColourList $colour_list)
	{
		$this->colours = $colour_list;
	}

	public function set_uri($uri)
	{
		$this->uri = $uri;
	}
	
	public function set_dimensions($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
	}
	
	public function set_mime(ImageMime $mime)
	{
		$this->mime = $mime;
	}
	
	public function add_colour($colour, &$image)
	{
		return $this->colours->add_colour($colour, $image);
	}
}
