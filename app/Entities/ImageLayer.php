<?php

namespace App\Entities;

use App\Entities\ImageProperties as ImageProperties;

/**
 * Description of ImageLayer
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ImageLayer
{
	private $image_data;
	private $properties;
	
	public function __construct($image_data, ImageProperties $properties)
	{
		$this->image_data = $image_data;
		$this->properties = $properties;
	}
}
