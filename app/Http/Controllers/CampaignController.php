<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\ImageGenerators\BaseImageGenerator as BaseImageGenerator;
use App\Entities\Image as Image;

/**
 * Description of CampaignController
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CampaignController extends BaseController
{
	private $config_helper;
	private $base_image_generator;
	private $image;

	public function __construct(ConfigHelper $config_helper, BaseImageGenerator $base_image_generator, Image $image)
	{
		$this->config_helper = $config_helper;
		$this->base_image_generator = $base_image_generator;
		$this->image = $image;
	}
	
	public function campaign_router()
	{
		$this->image->add_layer($this->base_image_generator->create_base_image($this->config_helper) );
		$image_generation_type = $this->config_helper->get('type');
		
		if(!method_exists($this, "build_$image_generation_type") )
		{
			throw new \App\Exceptions\UnsupportedImageGeneratorException("Unsupported image generation type: $image_generation_type");
		}
		else
		{
			$this->{"build_$image_generation_type"}();
		}
	}

	private function build_flat()
	{
		$this->apply_overlays_to_image_layer();
	}
	
	private function apply_overlays_to_image_layer($layer_index = 0)
	{
		$overlays = $this->config_helper->get('overlays');
		
		foreach($overlays as $overlay_name => $overlay_details)
		{
			$type = ucfirst($this->config_helper->get_for_overlay($overlay_name, 'type') );
			$overlay_generator_class = "App\\ImageGenerators\\{$type}Generator";
			
			$overlay_generator_class_instance = new $overlay_generator_class();
			
			$overlay_generator_class_instance->add($overlay_name);
		}
	}
}
