<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ConfigHelper as ConfigHelper;

/**
 * Description of CampaignController
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CampaignController extends BaseController
{
	private $config_helper;
	
	public function __construct(ConfigHelper $config_helper)
	{
		$this->config_helper = $config_helper;
	}
	
	public function campaign_router()
	{
		
	}

}
