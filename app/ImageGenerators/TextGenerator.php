<?php

namespace App\ImageGenerators;

use App\ImageGenerators\iImageGenerator as iImageGenerator;
use App\Entities\ImageLayer as ImageLayer;
use App\Helpers\ConfigHelper as ConfigHelper;

/**
 * Description of TextGenerator
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class TextGenerator implements iImageGenerator
{
	private $overlay_name;
	private $config_helper;
	
	public function __construct(ConfigHelper $config_helper)
	{
		$this->config_helper = $config_helper;
	}
	
	public function add(ImageLayer &$image, $overlay_name)
	{
		$this->overlay_name = $overlay_name;
		
		$bounding_width = $this->get_bounding_box_width();
		$bounding_height = $this->get_bounding_box_height();
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
