<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;
use App\Entities\Image as Image;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\ImageProperties as ImageProperties;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;

/**
 * Description of GooglemapGenerator
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class GooglemapGenerator implements iImageGenerator
{
	private $overlay_name;
	private $config_helper;
	private $image;
	private $image_properties;
	private $source_assets_helper;
	private $map_url = "https://maps.googleapis.com/maps/api/staticmap?";
	
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
		$center = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'center');
		$width = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'source_width', 640);
		$height = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'source_height', 480);
		$api_key = $this->config_helper->get_for_overlay($this->overlay_name, 'api_key');
		
		
		if(is_array($api_key) )
			$api_key = $api_key[rand(0, count($api_key)-1)];
		
		$params = array(
			'center' => urlencode($center),
			'zoom' => $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'zoom', 6),
			'size' => "{$width}x{$height}",
			'key' => $api_key,
		);
		$params['markers'] = $this->get_markers_string();
		
		
		$map_uri = $this->generate_map_url($params);
		
		$map_image = $this->get_map_image_from_api($map_uri);
		$this->place_map_on_image($map_image, $image_layer_index);
	}
	
	private function get_markers_string()
	{
		$markers_list = $this->config_helper->get_for_overlay($this->overlay_name, 'markers', []);
		$markers_colour = $this->config_helper->get_for_overlay($this->overlay_name, 'markers_colour', null);
		$markers_string = '';
		
		if(!empty($markers_list) )
		{
			if(!is_array($markers_list) )
				$markers_string = $markers_list;
			else
				$markers_string = urlencode(implode('|', $markers_list) );

			// set any custom marker colour if it's set
			if(!empty($markers_colour) && preg_match('/^#[0-9a-f]{6}$/i', $markers_colour) )
				$markers_string = 'color:0x' . substr($markers_colour, 1) . '%7C' . $markers_string;
		}
		
		return $markers_string;
	}
	
	private function place_map_on_image($map_image, $image_layer_index)
	{
		$x = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'x', 0);
		$y = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'y', 0);
		$source_width = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'source_width', 640);
		$source_height = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'source_height', 480);
		$dest_width = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'dest_width');
		$dest_height = $this->config_helper->get_for_overlay_with_request_param_substitution($this->overlay_name, 'dest_height');
		
		imagecopyresampled(
			$this->image[$image_layer_index]->image_data,
			$map_image,
			$x,
			$y,
			0,
			0,
			$dest_width,
			$dest_height,
			$source_width,
			$source_height
		);
	}
	
	private function get_map_image_from_api($map_uri)
	{
		// fetch the image from Google and create the GD object
		$ch = curl_init($map_uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$map_image_data = curl_exec($ch);
		
		$map_img = imagecreatefromstring($map_image_data);
		
		return $map_img;
	}
	
	private function generate_map_url(Array $params)
	{
		$uri = $this->map_url;
		
		foreach($params as $param => $value)
			$uri .= "$param=$value&";
		
		$uri = rtrim($uri, '&');
		
		return $uri;
	}
}
