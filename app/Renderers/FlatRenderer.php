<?php

namespace App\Renderers;

use App\Renderers\iRenderer as iRenderer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Helpers\CacheFileHelper as CacheFileHelper;
use App\Entities\Image as Image;


/**
 * Description of FlatRenderer
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class FlatRenderer implements iRenderer
{
	private $config_helper;
	private $cache_file_helper;
	
	public function __construct(ConfigHelper $config_helper, CacheFileHelper $cache_file_helper)
	{
		$this->config_helper = $config_helper;
		$this->cache_file_helper = $cache_file_helper;
	}
	
	public function render(Image $image)
	{
		$campaign_name = $this->config_helper->get_campaign_name();
		$record_id = $this->config_helper->get('record_id');
		$output_format = $this->config_helper->get('format', 'jpg');
		$output_filename = $this->cache_file_helper->get_output_filename($campaign_name, $output_format, $record_id);

		$image_data = $image[0]->image_data;
		
		$this->save_flat_image_if_uncached($image_data, $output_filename, $output_format);
		
		$this->output_image_with_content_headers($output_format, $image_data);
	}
	
	private function save_flat_image_if_uncached($image_data, $output_filename, $output_format)
	{
		$cache_duration = $this->cache_file_helper->get_cache_duration($this->config_helper->get('cache') );
		
		$cache_file_recent = $this->cache_file_helper->local_cache_file_recent($output_filename, $cache_duration);
		
		if(!$cache_file_recent)
		{
			$this->output_image($output_format, $image_data, $output_filename);
		}
	}
	
	private function output_image($output_format, $image_data, $filename = null)
	{
		switch($output_format)
		{
			case 'gif':
				imagegif($image_data, $filename);
				break;
			case 'png':
				imagepng($image_data, $filename);
				break;
			default:
				imagejpeg($image_data, $filename);
				break;
		}
	}
	
	private function output_image_with_content_headers($output_format, $image_data)
	{
		$this->output_image_headers($output_format);
		$this->output_image($output_format, $image_data);
	}
	
	private function output_image_headers($output_format)
	{
		if(in_array($output_format, ['gif', 'png']) )
		{
			header("Content-Type: image/$output_format");
		}
		else
		{
			header('Content-Type: image/jpeg');
		}
	}
}
