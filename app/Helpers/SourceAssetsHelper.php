<?php

namespace App\Helpers;

use App\Entities\ImageMime as ImageMime;
use App\Helpers\FileMimeHelper as FileMimeHelper;
use App\Entities\iImageProperties as iImageProperties;

/**
 * Description of SourceAssetsHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class SourceAssetsHelper
{
	private $base_path;
	private $file_mime_helper;
	
	public function __construct(FileMimeHelper $file_mime_helper)
	{
		$this->base_path = getcwd() . '/../source_assets';
		$this->file_mime_helper = $file_mime_helper;
	}
	
	public function get_real_source_path($base_uri)
	{
		$path = "$this->base_path/$base_uri";
		$real_path = realpath($path);

		if(!$real_path)
			throw new \App\Exceptions\InvalidImageBaseException("Invalid source asset $path specified");
		else
			return $real_path;
	}
	
	public function load_base_image($filename, ImageMime $mime)
	{
		switch($mime->get_extension() )
		{
			case 'jpeg':
				$base_image = imagecreatefromjpeg($filename);
				break;
			case 'png':
				$base_image = imagecreatefrompng($filename);
				imagealphablending($base_image, false);
				imagesavealpha($base_image, true);
				break;
			case 'gif':
				$base_image = imagecreatefromgif($filename);
				imagealphablending($base_image, false);
				imagesavealpha($base_image, true);
				break;
		}
		
		return $base_image;
	}

	public function create_base_image_from_existing($base_uri, iImageProperties &$image_properties)
	{
		$base_real_path = $this->get_real_source_path($base_uri);
		$mime = $this->file_mime_helper->get_mime_type_from_filename($base_uri);
		$image_data = $this->load_base_image($base_real_path, $mime);

		$image_properties->set_uri($base_real_path);
		$image_properties->set_dimensions(imagesx($image_data), imagesy($image_data));
		$image_properties->set_mime($mime);

		return $image_data;
	}
}
