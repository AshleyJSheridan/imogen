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
		$config_value = config("campaigns.$this->campaign_name.$option");
		
		if(!is_null($config_value) )
			return $config_value;
		else
			throw new \App\Exceptions\MissingConfigOptionException("$option is missing in config");
	}
	
	public function get_for_overlay($overlay, $option)
	{
		$overlay_config_value = config("campaigns.$this->campaign_name.overlays.$overlay.$option");
		
		if(!is_null($overlay_config_value) )
			return $overlay_config_value;
		else
		{
			try
			{
				$fallback_config_value = $this->get($option);
				
				return $fallback_config_value;
			}
			catch (MissingConfigOptionException $e)
			{
				throw new \App\Exceptions\MissingConfigOptionException("$option is missing in overlay config and no fallback available");
			}
		}
	}
}
