<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\ColourList as ColourList;
use App\Entities\NullImageProperties as NullImageProperties;
use App\Entities\ImageMime As ImageMime;

class EntityNullImagePropertiesTest extends TestCase
{
	private $properties;
	
	protected function setUp()
	{
		$this->properties = new NullImageProperties();
	}
	
	protected function tearDown()
	{
		unset($this->properties);
	}
	
	public function testSetDimensions()
	{
		$width = 10;
		$height = 20;
		
		$this->properties->set_dimensions($width, $height);
		
		$this->assertEquals($this->properties->get_width(), $width);
		$this->assertEquals($this->properties->get_height(), $height);
	}
	
	public function testSetMime()
	{
		$image_mime = new ImageMime();
		
		$this->properties->set_mime($image_mime);
		
		$this->assertEquals($this->properties->get_mime(), $image_mime);
	}
	
	public function testGetMimeAsString()
	{
		$image_mime = new ImageMime();
		$image_mime->set_mime('some mime string');
		
		$this->properties->set_mime($image_mime);
		
		$this->assertEquals($this->properties->get_mime_string(), (string)$image_mime);
	}
}
