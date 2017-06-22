<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;

/**
 * Description of TextGenerator
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class TextGenerator implements iImageGenerator
{
	public function add($overlay_name)
	{
		var_dump($overlay_name);
	}
}
