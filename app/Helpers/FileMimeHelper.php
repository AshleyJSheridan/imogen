<?php

namespace App\Helpers;

use App\Entities\ImageMime as ImageMime;

/**
 * Description of FileMimeHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class FileMimeHelper
{
	private $image_mime;
	
	public function __construct()
	{
		$this->image_mime = new ImageMime();
	}
	
	public function get_mime_type_from_filename($filename)
	{
		if(preg_match('/.(jpe?g|gif|png)$/', $filename, $matches) )
		{
			if($matches[1] == 'jpg')
				$matches[1] = 'jpeg';
			
			$this->image_mime->set_mime("image/{$matches[1]}");
			
			return $this->image_mime;
		}
		else
			throw new \App\Exceptions\UnrecognisedImageType("Unrecognised image type for $filename");
	}
	
	public function get_mime_from_extension($extension)
	{
		if($extension == 'gif' || $extension == 'png')
			$this->image_mime->set_mime("image/$extension");
		else
			$this->image_mime->set_mime("image/jpeg");
		
		return $this->image_mime;
	}
}
