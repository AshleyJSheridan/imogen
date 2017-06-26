<?php

namespace App\ImageGenerators;

use App\Entities\Image as Image;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iImageGenerator
{
	public function __construct($overlay_name, Image $image, ConfigHelper $config_helper, ImageProperties $image_properties);
	
	public function add($image_layer_index);
}
