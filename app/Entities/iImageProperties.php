<?php

namespace App\Entities;

/**
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
interface iImageProperties
{
	public function set_uri($uri);

	public function set_dimensions($width, $height);

	public function set_mime(ImageMime $mime);
	
	public function get_width();
	
	public function get_height();
	
	public function get_mime();
	
	public function get_mime_string();
}
