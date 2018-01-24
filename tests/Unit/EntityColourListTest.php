<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\ColourList As ColourList;
use App\Entities\Colour As Colour;

class EntityColourListTest extends TestCase
{
	private $colour_list;
	private $image;
	
	protected function setUp()
	{
		$this->colour_list = new ColourList();
		$this->image = imagecreatetruecolor(1, 1);
	}
	
	protected function tearDown()
	{
		unset($this->image);
	}

	public function testColourListEntityConstruction()
    {
        $this->assertTrue($this->colour_list instanceof ColourList);
    }
	
	public function testAddColour()
	{
		$colour = new Colour('fff');
		
		$image_colour_value = $this->colour_list->add_colour($colour, $this->image);
		
		$this->assertInternalType('int', $image_colour_value);
	}
	
	public function testColourKeyFetched()
	{
		$colour_string = '#fff';
		$mock_colour = $this->getMockBuilder(Colour::class)
			->setMethods(['get_string', '__construct'])
			->disableOriginalConstructor()
			->setConstructorArgs([$colour_string])
			->getMock();
		
		$mock_colour->expects($this->once())
			->method('get_string');
		
		$image_colour_value = $this->colour_list->add_colour($mock_colour, $this->image);
	}
}
