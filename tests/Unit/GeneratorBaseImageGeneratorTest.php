<?php

namespace App\ImageGenerators;

use Tests\TestCase;

class GeneratorBaseImageGeneratorTest extends TestCase
{
	private $source_assets_helper;
	private $file_mime_helper;
	private $image_properties;
	private $base_image;
	
	protected function setUp()
	{
		$this->source_assets_helper = $this->getMockBuilder(\App\Helpers\SourceAssetsHelper::class)
			->disableOriginalConstructor()
			->getMock();
		$this->file_mime_helper = $this->getMockBuilder(\App\Helpers\FileMimeHelper::class)
			->disableOriginalConstructor()
			->getMock();
		$this->image_properties = $this->getMockBuilder(\App\Entities\ImageProperties::class)
			->disableOriginalConstructor()
			->getMock();
	}
	
	protected function tearDown()
	{
		unset($this->source_assets_helper);
		unset($this->file_mime_helper);
		unset($this->image_properties);
	}
	
	public function testCanBeConstructed()
	{
		$base_image_generator = new BaseImageGenerator($this->source_assets_helper, $this->file_mime_helper, $this->image_properties);
		
		$this->assertTrue($base_image_generator instanceof BaseImageGenerator);
	}
	
	public function testCreateBaseFromNonEmptyBaseUri()
	{
		$config_helper = $this->getMockBuilder(\App\Helpers\ConfigHelper::class)
			->setMethods(['get'])
			->disableOriginalConstructor()
			->getMock();
		$config_helper->method('get')
			->willReturn('some base uri');
		$source_assets_helper = $this->getMockBuilder(\App\Helpers\SourceAssetsHelper::class)
			->disableOriginalConstructor()
			->getMock();
		$image_layer = $this->getMockBuilder(\App\Entities\ImageLayer::class)
			->setConstructorArgs(['some image data', $this->image_properties])
			->getMock();
		$source_assets_helper->expects($this->once())
			->method('create_base_image_from_existing')
			->willReturn($image_layer);
		
		$base_image_generator = new BaseImageGenerator($source_assets_helper, $this->file_mime_helper, $this->image_properties);
		$expected_base_image = new \App\Entities\ImageLayer($image_layer, $this->image_properties);
		
		$base_image = $base_image_generator->create_base_image($config_helper);
		
		$this->assertEquals($base_image, $expected_base_image);
	}
	
	public function testCreateBaseFromEmptyBaseUri()
	{
		$config_helper = $this->getMockBuilder(\App\Helpers\ConfigHelper::class)
			->setMethods(['get'])
			->disableOriginalConstructor()
			->getMock();
		$image_mime = new \App\Entities\ImageMime();
		$image_mime->set_mime('image/jpeg');
		$file_mime_helper = $this->getMockBuilder(\App\Helpers\FileMimeHelper::class)
			->setMethods(['get_mime_from_extension'])
			->disableOriginalConstructor()
			->getMock();
		$file_mime_helper->method('get_mime_from_extension')
			->willReturn($image_mime);
		$config_helper->expects($this->any())
			->method('get')
			->will($this->returnCallback(
				function($key)
				{
					switch($key)
					{
						case 'width':
						case 'height':
							return 1;
						case 'background':
							return '#ff0000';
						case 'format':
							return 'jpg';
					}
				}
			));
		$source_assets_helper = $this->getMockBuilder(\App\Helpers\SourceAssetsHelper::class)
			->disableOriginalConstructor()
			->getMock();
		
		$base_image_generator = new BaseImageGenerator($source_assets_helper, $file_mime_helper, $this->image_properties);
		
		$base_image = $base_image_generator->create_base_image($config_helper);

		$this->assertTrue($base_image instanceof \App\Entities\ImageLayer);
	}
	
	public function testCreateBaseFromEmptyBaseUriWithoutDimensions()
	{
		$config_helper = $this->getMockBuilder(\App\Helpers\ConfigHelper::class)
			->setMethods(['get'])
			->disableOriginalConstructor()
			->getMock();
		$image_mime = new \App\Entities\ImageMime();
		$image_mime->set_mime('image/jpeg');
		$file_mime_helper = $this->getMockBuilder(\App\Helpers\FileMimeHelper::class)
			->setMethods(['get_mime_from_extension'])
			->disableOriginalConstructor()
			->getMock();
		$file_mime_helper->method('get_mime_from_extension')
			->willReturn($image_mime);
		$config_helper->expects($this->any())
			->method('get')
			->will($this->returnCallback(
				function($key)
				{
					return null;
				}
			));
		$source_assets_helper = $this->getMockBuilder(\App\Helpers\SourceAssetsHelper::class)
			->disableOriginalConstructor()
			->getMock();
		
		$base_image_generator = new BaseImageGenerator($source_assets_helper, $file_mime_helper, $this->image_properties);
		
		$this->expectException(\App\Exceptions\InvalidImageBaseException::class);
		
		$base_image = $base_image_generator->create_base_image($config_helper);
	}
}
