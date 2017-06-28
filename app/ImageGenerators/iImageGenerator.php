<?php

namespace App\ImageGenerators;

use App\Entities\Image as Image;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iImageGenerator
{
	public function __construct($overlay_name, Image $image, ConfigHelper $config_helper, ImageProperties $image_properties, SourceAssetsHelper $source_assets_helper);
	
	public function add_from_config($image_layer_index);
}
