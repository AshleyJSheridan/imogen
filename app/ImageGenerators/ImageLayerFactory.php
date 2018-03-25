<?php

namespace App\ImageGenerators;

use App\Helpers\ConfigHelper as ConfigHelper;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;

/**
 * Description of ImageLayerFactory
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ImageLayerFactory
{
	private $config_helper;
	private $source_assets_helper;
	
	public function __construct(ConfigHelper $config_helper, SourceAssetsHelper $source_assets_helper)
	{
		$this->config_helper = $config_helper;
		$this->source_assets_helper = $source_assets_helper;
	}
	
	public function get_layer_class_instance($class_name, $overlay_name, $image)
	{
		$namespaced_classname = $this->get_clean_class_name($class_name);

		switch($class_name)
		{
			case 'text':
			{
				$overlay_generator_class_instance = new TextGenerator($overlay_name, $image, $this->config_helper, $image->get_image_properties(), $this->source_assets_helper);
				
				break;
			}
			case 'image':
			{
				$overlay_generator_class_instance = new ImageGenerator($overlay_name, $image, $this->config_helper, $image->get_image_properties(), $this->source_assets_helper);
				
				break;
			}
			case 'googlemap':
			{
				$overlay_generator_class_instance = new GooglemapGenerator($overlay_name, $image, $this->config_helper, $image->get_image_properties(), $this->source_assets_helper);

				break;
			}
		}
		
		return $overlay_generator_class_instance;
	}
	
	private function get_clean_class_name($class_name)
	{
		$clean_class_name = ucfirst(strtolower($class_name));
		$namespaced_class_name = "App\\ImageGenerators\\{$clean_class_name}Generator";
		
		return $namespaced_class_name;
	}
}
