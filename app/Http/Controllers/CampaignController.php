<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * Description of CampaignController
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CampaignController extends BaseController
{
	public function __construct()
	{
		
	}
	
	public function campaign_router($details_str = null)
	{
		$details = $this->get_campaign_details_from_string($details_str);
		
		var_dump($details);
	}

	private function get_campaign_details_from_string($details_str)
	{
		return explode('/', $details_str);
	}
}
