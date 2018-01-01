<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\Colour As Colour;

class ColourTest extends TestCase
{
	public function testColourEntityConstructionWithHash()
    {
		$colour_string = '#bada55';
		$colour = new Colour($colour_string);
		
        $this->assertTrue($colour instanceof Colour);
    }
	
	public function testColourEntityConstructionWithoutHash()
    {
		$colour_string = 'bada55';
		$colour = new Colour($colour_string);
		
        $this->assertTrue($colour instanceof Colour);
    }
	
	public function testReturnStringValue()
	{
		$colour_string = '#bada55';
		$expected_colour_string = 'bada55';
		$colour = new Colour($colour_string);
		
		$returnedColourString = $colour->get_string();
		
		$this->assertEquals($returnedColourString, $expected_colour_string);
	}
	
	public function testReturnStringValueWithRBGUnder10()
	{
		$colour_string = '#010101';
		$expected_colour_string = '010101';
		$colour = new Colour($colour_string);
		
		$returnedColourString = $colour->get_string();
		
		$this->assertEquals($returnedColourString, $expected_colour_string);
	}
	
	public function testReturnStringValueWithAlpha()
	{
		$colour_string = '#bada55e5';
		$expected_colour_string = 'bada55e5';
		$colour = new Colour($colour_string);
		
		$returnedColourString = $colour->get_string();
		
		$this->assertEquals($returnedColourString, $expected_colour_string);
	}
	
	public function testReturnStringValueWithAlphaAndValuesUnder10()
	{
		$colour_string = '#01010101';
		$expected_colour_string = '01010101';
		$colour = new Colour($colour_string);
		
		$returnedColourString = $colour->get_string();
		
		$this->assertEquals($returnedColourString, $expected_colour_string);
	}
	
	public function testGetRed()
	{
		$colour_string = '#bada55';
		$colour = new Colour($colour_string);
		$expected_colour_value = 186;
		
		$red = $colour->get_red();
		
		$this->assertEquals($red, $expected_colour_value);
	}
	
	public function testGetGreen()
	{
		$colour_string = '#bada55';
		$colour = new Colour($colour_string);
		$expected_colour_value = 218;
		
		$green = $colour->get_green();
		
		$this->assertEquals($green, $expected_colour_value);
	}
	
	public function testGetBlue()
	{
		$colour_string = '#bada55';
		$colour = new Colour($colour_string);
		$expected_colour_value = 85;
		
		$blue = $colour->get_blue();
		
		$this->assertEquals($blue, $expected_colour_value);
	}
	
	public function testGetAlpha()
	{
		$colour_string = '#bada55e5';
		$colour = new Colour($colour_string);
		$expected_colour_value = 229;
		
		$alpha = $colour->get_alpha();
		
		$this->assertEquals($alpha, $expected_colour_value);
	}
	
		public function testGetRedWithShortSyntax()
	{
		$colour_string = '#bd5';
		$colour = new Colour($colour_string);
		$expected_colour_value = 187;
		
		$red = $colour->get_red();
		
		$this->assertEquals($red, $expected_colour_value);
	}
	
	public function testGetGreenWithShortSyntax()
	{
		$colour_string = '#bd5';
		$colour = new Colour($colour_string);
		$expected_colour_value = 221;
		
		$green = $colour->get_green();
		
		$this->assertEquals($green, $expected_colour_value);
	}
	
	public function testGetBlueWithShortSyntax()
	{
		$colour_string = '#bd5';
		$colour = new Colour($colour_string);
		$expected_colour_value = 85;
		
		$blue = $colour->get_blue();
		
		$this->assertEquals($blue, $expected_colour_value);
	}
	
	public function testGetAlphaWithShortSyntax()
	{
		$colour_string = '#bd51';
		$colour = new Colour($colour_string);
		$expected_colour_value = 17;
		
		$alpha = $colour->get_alpha();
		
		$this->assertEquals($alpha, $expected_colour_value);
	}
}
