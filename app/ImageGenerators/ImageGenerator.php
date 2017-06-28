<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;
use App\Entities\Image as Image;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;

/**
 * Description of ImageGenerator
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ImageGenerator
{
	private $overlay_name;
	private $config_helper;
	private $image;
	private $image_properties;
	private $source_assets_helper;
	
	public function __construct($overlay_name, Image $image, ConfigHelper $config_helper, ImageProperties $image_properties)
	{
		$this->overlay_name = $overlay_name;
		$this->image = $image;
		$this->config_helper = $config_helper;
		$this->image_properties = $image_properties;
		$this->source_assets_helper = new SourceAssetsHelper();
	}
	
	public function add_from_config($image_layer_index)
	{
		
	}
}
