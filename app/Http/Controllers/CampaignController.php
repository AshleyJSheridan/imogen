<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ConfigHelper as ConfigHelper;
use App\ImageGenerators\BaseImageGenerator as BaseImageGenerator;

/**
 * Description of CampaignController
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CampaignController extends BaseController
{
	private $config_helper;
	private $base_image_generator;

	public function __construct(ConfigHelper $config_helper, BaseImageGenerator $base_image_generator)
	{
		$this->config_helper = $config_helper;
		$this->base_image_generator = $base_image_generator;
	}
	
	public function campaign_router()
	{
		$base_image = $this->base_image_generator->create_base_image($this->config_helper);
	}

}
