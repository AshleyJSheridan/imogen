<?php

namespace App\Renderers;

use App\Helpers\ConfigHelper as ConfigHelper;
use App\Entities\Image as Image;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iRenderer
{
	public function __construct(ConfigHelper $config_helper);
	
	public function render(Image $image);
}
