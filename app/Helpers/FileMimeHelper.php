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
	
	public function __construct(ImageMime $image_mime)
	{
		$this->image_mime = $image_mime;
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
}
