<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;
use App\Entities\Image as Image;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;

/**
 * Description of TextGenerator
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class TextGenerator implements iImageGenerator
{
	private $overlay_name;
	private $config_helper;
	private $image;
	private $image_properties;
	
	public function __construct(Image $image, ConfigHelper $config_helper, ImageProperties $image_properties)
	{
		$this->image = $image;
		$this->config_helper = $config_helper;
		$this->image_properties = $image_properties;
	}
	
	public function add($image_layer_index, $overlay_name)
	{
		$this->overlay_name = $overlay_name;
		
		$bounding_width = $this->get_bounding_box_width();
		$bounding_height = $this->get_bounding_box_height();
		
		$text = $this->get_text_content($overlay_name);
		$colour = $this->get_and_add_text_colour_to_image($image_layer_index, $overlay_name);
		
		$alignment = $this->config_helper->get_for_overlay($overlay_name, 'alignment');
		$v_alignment = $this->config_helper->get_for_overlay($overlay_name, 'v_alignment');
		$angle = $this->config_helper->get_for_overlay($overlay_name, 'angle');
		$font = $this->config_helper->get_for_overlay($overlay_name, 'font');
	}
	
	private function get_and_add_text_colour_to_image($image_layer_index, $overlay_name)
	{
		$colour_hex = $this->config_helper->get_for_overlay($overlay_name, 'colour');
		$colour = new \App\Entities\Colour($colour_hex);
		$colour_id = $this->image_properties->add_colour($colour, $this->image[$image_layer_index]->image_data);

		return $colour_id;
	}


	private function get_text_content($overlay_name)
	{
		$content_config_key = "overlays.$overlay_name.content";
		
		if(is_array($this->config_helper->get($content_config_key) ) )
			$content = $this->config_helper->get_random ($content_config_key);
		else
			$content = $this->config_helper->get($content_config_key);
		
		// replace placeholders with their actual content if necessary
		if(preg_match('/\{\{[^\}]+\}\}/', $content) )
			$content = $this->config_helper->substitute_content_with_request_params($content);
		
		return urldecode(filter_var($content, FILTER_SANITIZE_FULL_SPECIAL_CHARS) );
	}
	
	
	
	private function get_bounding_box_width()
	{
		return abs(
			$this->config_helper->get_for_overlay($this->overlay_name, 'x2')
			- $this->config_helper->get_for_overlay($this->overlay_name, 'x1')
		);
	}
	
	private function get_bounding_box_height()
	{
		return abs(
			$this->config_helper->get_for_overlay($this->overlay_name, 'y2')
			- $this->config_helper->get_for_overlay($this->overlay_name, 'y1')
		);
	}
}
