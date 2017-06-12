<?php

namespace App\Helpers;

/**
 * Description of SourceAssetsHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class SourceAssetsHelper
{
	private $base_path;
	
	public function __construct()
	{
		$this->base_path = getcwd() . '/../source_assets';
	}
	
	public function get_real_source_path($base_uri)
	{
		$path = "$this->base_path/$base_uri";
		$real_path = realpath($path);

		if(!$real_path)
			throw new \App\Exceptions\InvalidImageBaseException("Invalid source asset $path specified");
		else
			return $real_path;
	}
}
