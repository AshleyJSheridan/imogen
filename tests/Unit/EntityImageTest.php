<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\ImageLayer As ImageLayer;
use App\Entities\Image As Image;
use App\Entities\ImageProperties As ImageProperties;
use App\Entities\ColourList as ColourList;

class EntityImageTest extends TestCase
{
	private $colour_list;
	private $properties;
	private $image_data;
	private $image;
	private $layer1;
	private $layer2;
	
	protected function setUp()
	{
		$this->colour_list = new ColourList();
		$this->properties = new ImageProperties($this->colour_list);
		$this->image_data = 'some image data';
		$this->image = new Image();
		$this->layer1 = new ImageLayer($this->image_data, $this->properties);
		$this->layer2 = new ImageLayer($this->image_data, $this->properties);
	}

	protected function tearDown()
	{
		unset($this->image);
		unset($this->layer1);
		unset($this->layer2);
	}

	public function testAddImageLayer()
	{
		$this->image->add_layer($this->layer1);
		$this->image->add_layer($this->layer2);
		
		$this->assertEquals($this->image[0], $this->layer1);
		$this->assertEquals($this->image[1], $this->layer2);
	}
	
	public function testOffsetExistsWithKnownMember()
	{
		$this->image->add_layer($this->layer1);
		
		$this->assertTrue($this->image->offsetExists(0) );
	}
	
	public function testOffsetDoesNotExistWithUnknownMember()
	{
		$this->assertFalse($this->image->offsetExists(1) );
	}
	
	public function testOffsetWithKnownMember()
	{
		$this->image->add_layer($this->layer1);
		$layer = $this->image->offsetGet(0);
		
		$this->assertEquals($layer, $this->layer1);
	}
	
	public function testOffsetWithUnknownMember()
	{
		$layer = $this->image->offsetGet(1);
		$expected = null;
		
		$this->assertEquals($layer, $expected);
	}
	
	public function testOffsetSetWithNullIndex()
	{
		$some_value = 'some value';
		
		$this->image->offsetSet(null, $some_value);
		
		$this->assertEquals($this->image[0], $some_value);
	}
	
	public function testOffsetSetWithIndex()
	{
		$some_value = 'some value';
		$some_index = 10;
		
		$this->image->offsetSet($some_index, $some_value);
		
		$this->assertEquals($this->image[$some_index], $some_value);
	}
	
	public function testOffsetUnset()
	{
		$some_value = 'some value';
		$some_index = 10;
		
		$this->image->offsetSet($some_index, $some_value);
		$this->image->offsetUnset($some_index);
		
		$this->assertEquals($this->image[$some_index], null);
	}
}
