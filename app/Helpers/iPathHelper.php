<?php

namespace App\Helpers;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iPathHelper
{
	public function get_real_path($path);
	
	public function get_current_working_directory();
}
