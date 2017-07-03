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
		
	}
}
