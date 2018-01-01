<?php

namespace Tests\UnitEntities;

use Tests\TestCase;
use App\Entities\ImageLayer As ImageLayer;
use App\Entities\ImageProperties As ImageProperties;
use App\Entities\ColourList as ColourList;

class EntityImageLayerTest extends TestCase
{
	public function testImageLayerConstruction()
	{
		$colour_list = new ColourList();
		$properties = new ImageProperties($colour_list);
		$image_data = 'some image data';
		
		$layer = new ImageLayer($image_data, $properties);
		
		$this->assertEquals($layer->image_data, $image_data);
	}
}
