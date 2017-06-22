<?php

namespace App\ImageGenerators;

use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iImageGenerator
{
	public function __construct(ConfigHelper $config_helper);
	
	public function add(ImageLayer &$image, $overlay_name);
}
