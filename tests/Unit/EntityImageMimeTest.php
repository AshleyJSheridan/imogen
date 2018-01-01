<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\ImageMime As ImageMime;

class EntityImageMimeTest extends TestCase
{
	public function testSetMime()
	{
		$image_mime = new ImageMime();
		$mime_type = 'some/mime';
		
		$image_mime->set_mime($mime_type);
		
		$this->assertEquals((string)$image_mime, $mime_type);
	}
	
	public function testGetExtension()
	{
		$image_mime = new ImageMime();
		$mime_type = 'some/mime';
		$expected_extension = 'mime';
		
		$image_mime->set_mime($mime_type);
		
		$this->assertEquals($image_mime->get_extension(), $expected_extension);
	}
}
