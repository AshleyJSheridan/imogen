<?php

namespace App\Helpers;

use App\Entities\ConfigNullObject as ConfigNullObject;
use Illuminate\Http\Request;

/**
 * Description of ConfigHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ConfigHelper
{
	private $campaign_name;
	private $config;
	
	public function __construct(Request $request)
	{
		$this->campaign_name = $request->route('name');
	}
	
	public function get($option)
	{
		return config("campaigns.$this->campaign_name.$option");
	}
}
