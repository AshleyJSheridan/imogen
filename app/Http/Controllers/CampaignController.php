<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\ImageGenerators\BaseImageGenerator as BaseImageGenerator;
use App\Entities\Image as Image;
use App\Entities\ImageProperties as ImageProperties;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;
use App\Renderers\RenderFactory as RenderFactory;
use App\Helpers\CacheFileHelper as CacheFileHelper;

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
	private $image_properties;
	private $source_assets_helper;
	private $render_factory;
	private $cache_file_helper;

	public function __construct(ConfigHelper $config_helper, BaseImageGenerator $base_image_generator,
			Image $image, ImageProperties $image_properties, SourceAssetsHelper $source_assets_helper,
			RenderFactory $render_factory, CacheFileHelper $cache_file_helper)
	{
		$this->config_helper = $config_helper;
		$this->base_image_generator = $base_image_generator;
		$this->image = $image;
		$this->image_properties = $image_properties;
		$this->source_assets_helper = $source_assets_helper;
		$this->render_factory = $render_factory;
		$this->cache_file_helper = $cache_file_helper;
	}
	
	public function campaign_router()
	{
		$output_filename = $this->get_local_cache_filename();
		$cache_file_recent = $this->is_cache_file_recent($output_filename);
		$image_generation_type = $this->config_helper->get('type');
		
		$renderer = $this->render_factory->create();
		
		if($cache_file_recent)
		{
			$renderer->output_image_from_local_cache($output_filename);
			
			var_dump('recent');
			exit;
		}
		
		$this->image->add_layer($this->base_image_generator->create_base_image($this->config_helper) );
		
		if(!method_exists($this, "build_$image_generation_type") )
			throw new \App\Exceptions\UnsupportedImageGeneratorException("Unsupported image generation type: $image_generation_type");
		else
			$this->{"build_$image_generation_type"}();
		
		
		$renderer->render($this->image);
	}

	private function build_flat()
	{
		$this->apply_overlays_to_image_layer();
	}
	
	private function is_cache_file_recent($output_filename)
	{
		$cache_duration = $this->cache_file_helper->get_cache_duration($this->config_helper->get('cache') );
		
		$cache_file_recent = $this->cache_file_helper->local_cache_file_recent($output_filename, $cache_duration);
		
		return $cache_file_recent;
	}


	private function get_local_cache_filename()
	{
		$campaign_name = $this->config_helper->get_campaign_name();
		$record_id = $this->config_helper->get('record_id');
		$output_format = $this->config_helper->get('format', 'jpg');
		$output_filename = $this->cache_file_helper->get_output_filename($campaign_name, $output_format, $record_id);
		
		return $output_filename;
	}
	
	private function apply_overlays_to_image_layer($layer_index = 0)
	{
		$overlays = $this->config_helper->get('overlays');
		
		foreach($overlays as $overlay_name => $overlay_details)
		{
			$type = ucfirst($this->config_helper->get_for_overlay($overlay_name, 'type') );
			$overlay_generator_class = "App\\ImageGenerators\\{$type}Generator";
			
			$overlay_generator_class_instance = new $overlay_generator_class($overlay_name, $this->image, $this->config_helper, $this->image_properties, $this->source_assets_helper);
			
			$overlay_generator_class_instance->add_from_config($layer_index);
		}
	}
}
