<?php

namespace App\ImageGenerators;

use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;
use App\Helpers\FileMimeHelper as FileMimeHelper;
use App\Entities\ImageMime as ImageMime;
use App\Entities\ImageLayer as ImageLayer;
use App\Entities\ImageProperties as ImageProperties;

/**
 * Description of BaseImageGenerator
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class BaseImageGenerator
{
	private $source_assets_helper;
	private $file_mime_helper;


	public function __construct(SourceAssetsHelper $source_assets_helper, FileMimeHelper $file_mime_helper)
	{
		$this->source_assets_helper = $source_assets_helper;
		$this->file_mime_helper = $file_mime_helper;
	}
	
	public function create_base_image(\App\Helpers\ConfigHelper $config)
	{
		$base_uri = $config->get('base');
		
		if(!empty($base_uri) )
		{
			$base_image = $this->create_base_image_from_existing($base_uri);
		}
		else
		{
			if(!empty($config->get('width') ) && !empty($config->get('height') ) )
			{
				
			}
			else
			{
				throw new App\Exceptions\InvalidImageBaseException('Invalid base image, or width and height base values');
			}
		}

		return $base_image;
	}
	
	private function create_base_image_from_existing($base_uri)
	{
		$base_real_path = $this->source_assets_helper->get_real_source_path($base_uri);
		$mime = $this->file_mime_helper->get_mime_type_from_filename($base_uri);
		$image_data = $this->load_base_image($base_real_path, $mime);

		$base_image_properties = new ImageProperties($base_real_path, imagesx($image_data), imagesy($image_data), $mime);
		$base_image = new ImageLayer($image_data, $base_image_properties);
		
		return $base_image;
	}
	
	private function load_base_image($filename, ImageMime $mime)
	{
		switch($mime->get_extension() )
		{
			case 'jpeg':
				$base_image = imagecreatefromjpeg($filename);
				break;
			case 'png':
				$base_image = imagecreatefrompng($filename);
				break;
			case 'gif':
				$base_image = imagecreatefromgif($filename);
				break;
		}
		
		return $base_image;
	}
}
