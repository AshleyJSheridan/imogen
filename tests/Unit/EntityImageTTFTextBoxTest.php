<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\ImageTTFBox as ImageTTFBox;

class EntityImageTTFTextBoxTest extends TestCase
{
	public function testImageTTFBoxConstructor()
	{
		$width = 100;
		$height = 200;
		$fits = true;
		$baseline_offset = 2;
		
		$ttfTextBox = new ImageTTFBox($width, $height, $fits, $baseline_offset);
		
		$this->assertEquals($ttfTextBox->get_box_width(), $width);
		$this->assertEquals($ttfTextBox->get_box_height(), $height);
		$this->assertEquals($ttfTextBox->get_fits(), $fits);
		$this->assertEquals($ttfTextBox->get_baseline_offset(), $baseline_offset);
	}
}
