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
	private $image_properties;
	private $base_image;

	public function __construct(SourceAssetsHelper $source_assets_helper, FileMimeHelper $file_mime_helper, ImageProperties $image_properties)
	{
		$this->source_assets_helper = $source_assets_helper;
		$this->file_mime_helper = $file_mime_helper;
		$this->image_properties = $image_properties;
	}
	
	public function create_base_image(\App\Helpers\ConfigHelper $config)
	{
		$base_uri = $config->get('base');
		
		if(!empty($base_uri) )
		{
			$this->base_image = $this->source_assets_helper->create_base_image_from_existing($base_uri, $this->image_properties);
		}
		else
		{
			if(!empty($config->get('width') ) && !empty($config->get('height') ) )
			{
				$this->base_image = $this->create_blank_base_image($config->get('width'), $config->get('height'), $config->get('background'), $config->get('format') );
			}
			else
			{
				throw new App\Exceptions\InvalidImageBaseException('Invalid base image, or width and height base values');
			}
		}

		$base_image = new ImageLayer($this->base_image, $this->image_properties);

		return $base_image;
	}
	
	private function create_blank_base_image($width, $height, $background, $format)
	{
		$base_image = imagecreatetruecolor($width, $height);
		$base_mime = $this->file_mime_helper->get_mime_from_extension($format);
		
		$this->image_properties->set_dimensions($width, $height);
		$this->image_properties->set_mime($base_mime);
		
		if(!is_null($background))
		{
			$fill_colour = new \App\Entities\Colour($background);
			$fill_id = $this->image_properties->add_colour($fill_colour, $base_image);

			imagefilledrectangle($base_image, 0, 0, $width, $height, $fill_id);
		}

		return $base_image;
	}
}
