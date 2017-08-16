<?php

namespace App\Renderers;

use App\Helpers\ConfigHelper as ConfigHelper;
use App\Helpers\CacheFileHelper as CacheFileHelper;
use App\Entities\Image as Image;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iRenderer
{
	public function __construct(ConfigHelper $config_helper, CacheFileHelper $cache_file_helper);
	
	public function render(Image $image);
	
}
