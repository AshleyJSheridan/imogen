<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;
use App\Entities\Image as Image;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;
use App\Entities\iImageProperties as iImageProperties;
use App\Entities\NullImageProperties as NullImageProperties;

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
	
	public function __construct($overlay_name, Image $image, ConfigHelper $config_helper, ImageProperties $image_properties, SourceAssetsHelper $source_assets_helper)
	{
		$this->overlay_name = $overlay_name;
		$this->image = $image;
		$this->config_helper = $config_helper;
		$this->image_properties = $image_properties;
		$this->source_assets_helper = $source_assets_helper;
	}
	
	public function add_from_config($image_layer_index)
	{
		$base_uri = $this->config_helper->get_for_overlay($this->overlay_name, 'url');
		$overlay_image_properties = new NullImageProperties();
		$image = $this->source_assets_helper->create_base_image_from_existing($base_uri, $overlay_image_properties);
		
		$this->place_image_on_image($image, $image_layer_index);
	}
	
	private function place_image_on_image($overlay_image, $image_layer_index)
	{
		$angle = $this->config_helper->get_for_overlay($this->overlay_name, 'angle', 0, false);
		
		if($angle)
		{
			$transparent = imagecolorallocatealpha($overlay_image, 0, 0, 0, 127);
			$overlay_image = imagerotate($overlay_image, $angle, $transparent);
		}
		
		$actual_width = imagesx($overlay_image);
		$actual_height = imagesy($overlay_image);

		$dest_width = $this->config_helper->get_for_overlay($this->overlay_name, 'width', $actual_width);
		$dest_height = $this->config_helper->get_for_overlay($this->overlay_name, 'height', $actual_height);
		
		$x = $this->config_helper->get_for_overlay($this->overlay_name, 'x');
		$y = $this->config_helper->get_for_overlay($this->overlay_name, 'y');
		
		$copied = imagecopyresampled(
			$this->image[$image_layer_index]->image_data,
			$overlay_image,
			$x,
			$y,
			0,
			0,
			$dest_width,
			$dest_height,
			$actual_width,
			$actual_height
		);
	}
}
