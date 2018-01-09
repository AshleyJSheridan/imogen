<?php

namespace App\Helpers;

use Illuminate\Http\Request;


/**
 * Description of ConfigRequestSubstitutionHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ConfigRequestSubstitutionHelper
{
	private $request;
	
	public function __construct(Request $request)
	{
		$this->request = $request;
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
}
