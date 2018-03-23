<?php

namespace App\Helpers;

use App\Entities\ConfigNullObject as ConfigNullObject;
use Illuminate\Http\Request;
use App\Helpers\ConfigRequestSubstitutionHelper as ConfigRequestSubstitutionHelper;

/**
 * Description of ConfigHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ConfigHelper
{
	private $campaign_name;
	private $config;
	private $request;
	private $requestSubstitutionsHelper;
	
	public function __construct(Request $request, ConfigRequestSubstitutionHelper $requestSubstitutionsHelper)
	{
		$this->request = $request;
		$this->requestSubstitutionsHelper = $requestSubstitutionsHelper;
		$this->campaign_name = $this->request->route('name');
	}
	
	public function get_campaign_name()
	{
		return $this->campaign_name;
	}

	public function get($option, $default = null)
	{
		$config_value = config("campaigns.$this->campaign_name.$option");
		
		if(!is_null($config_value) )
		{
			return $config_value;
		}
		else
		{
			return $default;
		}
	}
	
	public function get_for_overlay($overlay, $option, $default_value = null, $required = true)
	{
		$overlay_config_value = config("campaigns.$this->campaign_name.overlays.$overlay.$option");
		
		if(!is_null($overlay_config_value) )
		{
			return $this->requestSubstitutionsHelper->substitute_content_with_request_params($overlay_config_value);
		}
		else
		{
			try
			{
				return $this->get_fallback_or_default_value($option, $default_value, $required);
			}
			catch (\App\Exceptions\MissingConfigOptionException $e)
			{
				throw new \App\Exceptions\MissingConfigOptionException("$option is missing in overlay config and no fallback available");
			}
		}
	}
	
	public function get_fallback_or_default_value($option, $default_value = null, $required = true)
	{
		$fallback_config_value = $this->get($option);

		if(is_null($fallback_config_value) )
		{
			if($required)
			{
				throw new \App\Exceptions\MissingConfigOptionException("$option is missing in config");
			}
			else
			{
				return $default_value;
			}
		}

		return $fallback_config_value;		
	}
	
	public function get_random($overlay, $option)
	{
		$content_array = $this->get_for_overlay($overlay, $option);
		
		return $content_array[rand(0, count($content_array) - 1 )];
	}

	public function substitute_content_with_request_params($content)
	{
		return $this->requestSubstitutionsHelper->substitute_content_with_request_params($content);
	}
	
	public function get_for_overlay_with_request_param_substitution($overlay, $option, $default_value = null, $required = true)
	{
		$config_value = $this->get_for_overlay($overlay, $option, $default_value, $required);
		
		$subsituted_config_value = $this->requestSubstitutionsHelper->substitute_content_with_request_params($config_value);
		
		return $subsituted_config_value;
	}
}
