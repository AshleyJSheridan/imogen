<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;
use App\Entities\Image as Image;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;
use App\Entities\ImageTTFBox as ImageTTFBox;

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
		$bounding_width = $this->get_bounding_box_width();
		$bounding_height = $this->get_bounding_box_height();
		
		$text = $this->get_text_content($this->overlay_name);
		$colour = $this->get_and_add_text_colour_to_image($image_layer_index, $this->overlay_name);
		
		$this->write_text($bounding_width, $bounding_height, $text, $colour, $this->overlay_name, $image_layer_index);
	}
	
	private function write_text($bounding_width, $bounding_height, $text, $colour, $overlay_name, $image_layer_index)
	{
		$alignment = $this->config_helper->get_for_overlay($overlay_name, 'alignment');
		$v_alignment = $this->config_helper->get_for_overlay($overlay_name, 'v_alignment');
		$angle = $this->config_helper->get_for_overlay($overlay_name, 'angle');
		$font = $this->config_helper->get_for_overlay($overlay_name, 'font');
		$size = $this->config_helper->get_for_overlay($overlay_name, 'size');
		$min_size = $this->config_helper->get_for_overlay($overlay_name, 'min_size');
		
		$fits = false;
		for($size; $size >= $min_size; $size--)
		{
			$text_box = $this->text_fits_into_box_at_size($font, $size, $text, $angle, $bounding_width, $bounding_height);
			
			if($text_box->get_fits() )
			{
				$fits = true;
				
				$x1 = $this->config_helper->get_for_overlay($overlay_name, 'x1');
				$x2 = $this->config_helper->get_for_overlay($overlay_name, 'x2');
				$y1 = $this->config_helper->get_for_overlay($overlay_name, 'y1');
				$y2 = $this->config_helper->get_for_overlay($overlay_name, 'y2');
				
				$x = $this->get_position($bounding_width, $text_box->get_box_width(), $x1, $x2, $alignment);
				// subtracting the baseline offset to allow because ttftextbox doesn't account for it normally
				$y = $this->get_position($bounding_height, $text_box->get_box_height(), $y1, $y2, $v_alignment) - $text_box->get_baseline_offset();
				
				$font_file = $this->source_assets_helper->get_real_source_path($font);

				imagettftext($this->image[$image_layer_index]->image_data, $size, $angle, $x, $y, $colour, $font_file, $text);
				
				break;
			}

		}
	}
	
	public function get_position($bounding_dimension, $dimension, $coord_1, $coord_2, $alignment)
	{
		$pos = 0;
		
		switch($alignment)
		{
			case 'left':
			case 'top':
				$pos = $coord_1;
				break;
			case 'center':
				$pos = ($bounding_dimension - $dimension) / 2 + $coord_1;
				break;
			case 'right':
			case 'bottom':
				$pos = $coord_2 - $dimension;
				break;
		}

		return $pos;
	}
	
	private function text_fits_into_box_at_size($font, $size, $text, $angle, $bounding_width, $bounding_height)
	{
		$font_file = $this->source_assets_helper->get_real_source_path($font);
		
		$box = imagettfbbox($size, $angle, $font_file, $text);
		
		$box_width = abs($box[4] - $box[0]);
		$box_height = abs($box[5] - $box[1]);
		
		$text_fits = ($box_width < $bounding_width && $box_height < $bounding_height);
		$image_text_box = new ImageTTFBox($box_width, $box_height, $text_fits, $box[5]);
		
		return $image_text_box;
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
