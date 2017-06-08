<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Entities\RequestParams as RequestParams;

/**
 * Description of CampaignController
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CampaignController extends BaseController
{
	private $request_params;
	
	public function __construct(RequestParams $request_params)
	{
		$this->request_params = $request_params;
	}
	
	public function campaign_router($details_str = null)
	{
		$details = $this->get_campaign_details_from_string($details_str);
		
		
	}

	private function get_campaign_details_from_string($details_str)
	{
		return explode('/', $details_str);
	}
}
