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
	private $request;
	
	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->campaign_name = $this->request->route('name');
	}
	
	public function get($option, $default = null)
	{
		$config_value = config("campaigns.$this->campaign_name.$option");
		
		if(!is_null($config_value) )
			return $config_value;
		else
			return $default;
	}
	
	public function get_for_overlay($overlay, $option, $default_value = null, $required = true)
	{
		$overlay_config_value = config("campaigns.$this->campaign_name.overlays.$overlay.$option");
		
		if(!is_null($overlay_config_value) )
		{
			$overlay_config_value = $this->substitute_content_with_request_params($overlay_config_value);
			
			return $overlay_config_value;
		}
		else
		{
			if($default_value)
				return $default_value;
			else
			{
				try
				{
					$fallback_config_value = $this->get($option);

					if(is_null($fallback_config_value) )
					{
						if($required)
							throw new \App\Exceptions\MissingConfigOptionException("$option is missing in config");
						else
							return $default_value;
					}

					return $fallback_config_value;
				}
				catch (MissingConfigOptionException $e)
				{
					throw new \App\Exceptions\MissingConfigOptionException("$option is missing in overlay config and no fallback available");
				}
			}
		}
	}
	
	public function get_random($option)
	{
		$content_array = $this->get_for_overlay($option);
		
		return $content_array[rand(0, count($content_array) - 1 )];
	}
	
	public function substitute_content_with_request_params($content)
	{
		// the regular expression matches any substring in the form {{substring}} and replaces it with
		// the value of the corresponding request parameter of the same key
		$substituted_content = preg_replace_callback(
			'/\{\{([^\}]+)\}\}/',
			function($matches) {
				$param_key = $matches[1];
			
				return $this->request->get($param_key);
			},
			$content
		);
		
		return $substituted_content;
	}
	
	public function get_for_overlay_with_request_param_substitution($overlay, $option, $default_value = null, $required = true)
	{
		$config_value = $this->get_for_overlay($overlay, $option, $default_value, $required);
		
		$subsituted_config_value = $this->substitute_content_with_request_params($config_value);
		
		return $subsituted_config_value;
	}
}
