<?php

namespace App\Renderers;

use App\Renderers\iRenderer as iRenderer;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\Image as Image;


/**
 * Description of FlatRenderer
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class FlatRenderer implements iRenderer
{
	private $config_helper;
	
	public function __construct(ConfigHelper $config_helper)
	{
		$this->config_helper = $config_helper;
	}
	
	public function render(Image $image)
	{
		$output_format = $this->config_helper->get('format', 'jpg');
		$image_data = $image[0]->image_data;
		
		switch($output_format)
		{
			case 'gif':
				header('Content-Type: image/gif');
				imagegif($image_data);
				break;
			case 'png':
				header('Content-Type: image/png');
				imagepng($image_data);
				break;
			default:
				header('Content-Type: image/jpeg');
				imagejpeg($image_data);
				break;
		}
	}
}
