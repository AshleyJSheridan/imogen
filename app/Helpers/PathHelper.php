<?php

namespace App\Helpers;

/**
 * Description of PathHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class PathHelper implements iPathHelper
{
	public function get_real_path($path)
	{
		return realpath($path);
	}
	
	public function get_current_working_directory()
	{
		return getcwd();
	}
}
