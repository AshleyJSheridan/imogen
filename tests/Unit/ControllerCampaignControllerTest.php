<?php

namespace App\Http\Controllers;

use Tests\TestCase;

class ControllerCampaignControllerTest extends TestCase
{
	private $config_helper;
	private $base_image_generator;
	private $image;
	private $source_assets_helper;
	private $render_factory;
	private $cache_file_helper;
	private $image_layer_factory;
	
	protected function setUp()
	{
		$this->config_helper = $this->getMockBuilder(\App\Helpers\ConfigHelper::class)
			->disableOriginalConstructor()
			->getMock();
		$this->base_image_generator = $this->getMockBuilder(\App\ImageGenerators\BaseImageGenerator::class)
			->disableOriginalConstructor()
			->getMock();
		$this->image = $this->getMockBuilder(\App\Entities\Image::class)
			->disableOriginalConstructor()
			->getMock();
		$this->source_assets_helper = $this->getMockBuilder(\App\Helpers\SourceAssetsHelper::class)
			->disableOriginalConstructor()
			->getMock();
		$this->render_factory = $this->getMockBuilder(\App\Renderers\RenderFactory::class)
			->disableOriginalConstructor()
			->getMock();
		$this->cache_file_helper = $this->getMockBuilder(\App\Helpers\CacheFileHelper::class)
			->disableOriginalConstructor()
			->getMock();
		$this->image_layer_factory = $this->getMockBuilder(\App\ImageGenerators\ImageLayerFactory::class)
			->disableOriginalConstructor()
			->getMock();
	}
	
	protected function tearDown()
	{
		unset($this->config_helper);
		unset($this->base_image_generator);
		unset($this->image);
		unset($this->source_assets_helper);
		unset($this->render_factory);
		unset($this->cache_file_helper);
		unset($this->image_layer_factory);
	}
	
	public function testCanBeConstructed()
	{
		$controller = $this->getMockBuilder(CampaignController::class)
			->setConstructorArgs([
				$this->config_helper,
				$this->base_image_generator,
				$this->image,
				$this->source_assets_helper,
				$this->render_factory,
				$this->cache_file_helper,
				$this->image_layer_factory
			])
			->getMock();
		
		$this->assertTrue($controller instanceof CampaignController);
	}
	
	public function testCampaignRouterWithRecentlyCachedFile()
	{
		$mock_renderer = $this->getMockBuilder(MockRenderer::class)
			->setMethods(['output_image_from_local_cache'])
			->getMock();
		$mock_renderer->expects($this->once())
			->method('output_image_from_local_cache');
		$render_factory = $this->getMockBuilder(\App\Renderers\RenderFactory::class)
			->setMethods(['create', 'get_renderer_class_name'])
			->disableOriginalConstructor()
			->getMock();
		$render_factory->expects($this->once())
			->method('create')
			->willReturn($mock_renderer);
		$cache_file_helper = $this->getMockBuilder(\App\Helpers\CacheFileHelper::class)
			->setMethods(['get_cache_duration', 'local_cache_file_recent', 'get_output_filename'])
			->disableOriginalConstructor()
			->getMock();
		$cache_file_helper->expects($this->once())
			->method('local_cache_file_recent')
			->willReturn(true);
		$controller = $this->getMockBuilder(CampaignController::class)
			->setMethods(['get_local_cache_filename', 'is_cache_file_recent'])
			->setConstructorArgs([
				$this->config_helper,
				$this->base_image_generator,
				$this->image,
				$this->source_assets_helper,
				$render_factory,
				$cache_file_helper,
				$this->image_layer_factory
			])
			->getMock();
		
		$controller->campaign_router();
	}
	
	public function testCampaignRouterWithNoRecentCachedFile()
	{
		$config_helper = $this->getMockBuilder(\App\Helpers\ConfigHelper::class)
			->disableOriginalConstructor()
			->setMethods(['get'])
			->getMock();
		$config_helper->method('get')
			->willReturn('thing');
		$mock_renderer = $this->getMockBuilder(MockRenderer::class)
			->setMethods(['render'])
			->getMock();
		$mock_renderer->expects($this->once())
			->method('render');
		$render_factory = $this->getMockBuilder(\App\Renderers\RenderFactory::class)
			->setMethods(['create', 'get_renderer_class_name'])
			->disableOriginalConstructor()
			->getMock();
		$render_factory->expects($this->once())
			->method('create')
			->willReturn($mock_renderer);
		$cache_file_helper = $this->getMockBuilder(\App\Helpers\CacheFileHelper::class)
			->setMethods(['get_cache_duration', 'local_cache_file_recent', 'get_output_filename'])
			->disableOriginalConstructor()
			->getMock();
		$cache_file_helper->expects($this->once())
			->method('local_cache_file_recent')
			->willReturn(false);
		$image_layer = $this->getMockBuilder(\App\Entities\ImageLayer::class)
			->disableOriginalConstructor()
			->getMock();
		$base_image_generator = $this->getMockBuilder(\App\ImageGenerators\BaseImageGenerator::class)
			->disableOriginalConstructor()
			->setMethods(['create_base_image'])
			->getMock();
		$base_image_generator->method('create_base_image')
			->willReturn($image_layer);
		$image = $this->getMockBuilder(\App\Entities\Image::class)
			->setMethods(['add_layer'])
			->disableOriginalConstructor()
			->getMock();
		$image->expects($this->once())
			->method('add_layer');
		$controller = $this->getMockBuilder(CampaignController::class)
			->setMethods(['get_local_cache_filename', 'is_cache_file_recent', 'build_thing'])
			->setConstructorArgs([
				$config_helper,
				$base_image_generator,
				$image,
				$this->source_assets_helper,
				$render_factory,
				$cache_file_helper,
				$this->image_layer_factory
			])
			->getMock();
		$controller->expects($this->once())
			->method('build_thing');
		
		$controller->campaign_router();
	}
	
	public function testCampaignRouterWithNoRecentCachedFileAndUnsupportedBuildMethod()
	{
		$config_helper = $this->getMockBuilder(\App\Helpers\ConfigHelper::class)
			->disableOriginalConstructor()
			->setMethods(['get'])
			->getMock();
		$config_helper->method('get')
			->willReturn('unsupported_thing');
		$mock_renderer = $this->getMockBuilder(MockRenderer::class)
			->setMethods(['render'])
			->getMock();
		$render_factory = $this->getMockBuilder(\App\Renderers\RenderFactory::class)
			->setMethods(['create', 'get_renderer_class_name'])
			->disableOriginalConstructor()
			->getMock();
		$render_factory->expects($this->once())
			->method('create')
			->willReturn($mock_renderer);
		$cache_file_helper = $this->getMockBuilder(\App\Helpers\CacheFileHelper::class)
			->setMethods(['get_cache_duration', 'local_cache_file_recent', 'get_output_filename'])
			->disableOriginalConstructor()
			->getMock();
		$cache_file_helper->expects($this->once())
			->method('local_cache_file_recent')
			->willReturn(false);
		$image_layer = $this->getMockBuilder(\App\Entities\ImageLayer::class)
			->disableOriginalConstructor()
			->getMock();
		$base_image_generator = $this->getMockBuilder(\App\ImageGenerators\BaseImageGenerator::class)
			->disableOriginalConstructor()
			->setMethods(['create_base_image'])
			->getMock();
		$base_image_generator->method('create_base_image')
			->willReturn($image_layer);
		$image = $this->getMockBuilder(\App\Entities\Image::class)
			->setMethods(['add_layer'])
			->disableOriginalConstructor()
			->getMock();
		$image->expects($this->once())
			->method('add_layer');
		$controller = $this->getMockBuilder(CampaignController::class)
			->setMethods(['get_local_cache_filename', 'is_cache_file_recent'])
			->setConstructorArgs([
				$config_helper,
				$base_image_generator,
				$image,
				$this->source_assets_helper,
				$render_factory,
				$cache_file_helper,
				$this->image_layer_factory
			])
			->getMock();
		
		$this->expectException(\App\Exceptions\UnsupportedImageGeneratorException::class);
		
		$controller->campaign_router();
	}
}

class MockRenderer
{
	public function output_image_from_local_cache($filename)
	{
		return $filename;
	}
	
	public function render($image)
	{
		return $image;
	}
}
