<?php

namespace App\Helpers;

use Tests\TestCase;
use App\Helpers\FileMimeHelper as FileMimeHelper;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;
use App\Entities\ImageMime as ImageMime;
use App\Entities\iImageProperties as iImageProperties;

class HelperSourceAssetsHelperTest extends TestCase
{
	private $file_mime_helper;
	private $path_helper;
	private $mock_helper;
	private $helper;
	
	protected function setUp()
	{
		$this->file_mime_helper = new FileMimeHelper();
		$this->path_helper = new mockPathHelper();
		
		$this->mock_helper = $this->getMockBuilder(SourceAssetsHelper::class)
			->setConstructorArgs(array($this->file_mime_helper, $this->path_helper))
			->getMock();
		
		$this->helper = new SourceAssetsHelper($this->file_mime_helper, $this->path_helper);
	}
	
	protected function tearDown()
	{
		unset($this->file_mime_helper);
		unset($this->path_helper);
		unset($this->mock_helper);
		unset($this->helper);
	}

	public function testSourceAssetsHelperConstruction()
	{
		$this->assertTrue($this->helper instanceof SourceAssetsHelper);
	}
	
	public function testGetRealSourcePath()
	{
		$base_uri = 'somepath';
		
		$real_path = $this->helper->get_real_source_path($base_uri);
		
		$this->assertEquals($real_path, 'some real path');
	}
	
	public function testLoadBaseImageWithJpeg()
	{
		$mime = new ImageMime();
		$mime->set_mime('image/jpeg');
		$filename = 'some filename';
		
		$base_image = $this->helper->load_base_image($filename, $mime);
		
		$this->assertEquals($base_image, 'jpeg image');
	}
	
	public function testLoadBaseImageWithPng()
	{
		$mime = new ImageMime();
		$mime->set_mime('image/png');
		$filename = 'some filename';

		$base_image = $this->helper->load_base_image($filename, $mime);
		
		$this->assertEquals($base_image, 'png image');
	}
	
	public function testLoadBaseImageWithGif()
	{
		$mime = new ImageMime();
		$mime->set_mime('image/gif');
		$filename = 'some filename';

		$base_image = $this->helper->load_base_image($filename, $mime);
		
		$this->assertEquals($base_image, 'gif image');
	}

	public function testGetBaseImageFromCreateBaseImageFromExisting()
	{
		$base_uri = 'some filename';

		$mock_image_properties = $this->getMockBuilder('App\Entities\iImageProperties')
			->getMock();

		$mock_assets_helper = $this->getMockBuilder(SourceAssetsHelper::class)
			->setMethods(['__construct', 'get_real_source_path', 'load_base_image', 'get_mime_type_from_filename'])
			->disableOriginalConstructor()
			->getMock();
		
		$mock_image_mime = $this->getMockBuilder('App\Entities\ImageMime')
			->getMock();
		$mock_gd_image = imagecreatetruecolor(1, 1);

		$mock_assets_helper->expects($this->once())
			->method('get_real_source_path');
		$mock_assets_helper->expects($this->once())
			->method('get_mime_type_from_filename')
			->willReturn($mock_image_mime);
		$mock_assets_helper->expects($this->once())
			->method('load_base_image')
			->willReturn($mock_gd_image);
		
		$image_data = $mock_assets_helper->create_base_image_from_existing($base_uri, $mock_image_properties);
		
		$this->assertEquals(get_resource_type($image_data), 'gd');
	}
	
	public function testImagePropertiesSetFromCreateBaseImageFromExisting()
	{
		$base_uri = 'some filename';

		$mock_image_properties = $this->getMockBuilder('App\Entities\iImageProperties')
			->setMethods(['set_uri', 'set_dimensions', 'set_mime', 'get_width', 'get_height', 'get_mime', 'get_mime_string'])
			->getMock();

		$mock_assets_helper = $this->getMockBuilder(SourceAssetsHelper::class)
			->setMethods(['__construct', 'get_real_source_path', 'load_base_image', 'get_mime_type_from_filename'])
			->disableOriginalConstructor()
			->getMock();
		
		$mock_image_mime = $this->getMockBuilder('App\Entities\ImageMime')
			->getMock();
		$mock_gd_image = imagecreatetruecolor(1, 1);
		
		$mock_assets_helper->method('get_mime_type_from_filename')
			->willReturn($mock_image_mime);
		$mock_assets_helper->method('load_base_image')
			->willReturn($mock_gd_image);
		
		$mock_image_properties->expects($this->once())
			->method('set_uri');
		$mock_image_properties->expects($this->once())
			->method('set_dimensions');
		$mock_image_properties->expects($this->once())
			->method('set_mime');

		$mock_assets_helper->create_base_image_from_existing($base_uri, $mock_image_properties);
	}
	
	public function testGetMimeTypeFromFilename()
	{
		$filename = 'some filename';
		$some_mime = 'some mime type';
		$path_helper =  new PathHelper();
		$mock_file_mime_helper = $this->getMockBuilder('App\Helpers\FileMimeHelper')
			->setMethods(['get_mime_type_from_filename'])
			->getMock();
		
		$mock_file_mime_helper->expects($this->once())
			->method('get_mime_type_from_filename')
			->willReturn($some_mime);
		
		$assets_helper = new SourceAssetsHelper($mock_file_mime_helper, $path_helper);
		
		$mime_type = $assets_helper->get_mime_type_from_filename($filename);
		
		$this->assertEquals($mime_type, $some_mime);
	}
}

class mockPathHelper extends PathHelper
{
	public function get_real_path($path)
	{
		return 'some real path';
	}
	
	public function get_current_working_directory()
	{
		return 'some_dir';
	}
}

function imagecreatefromjpeg($filename)
{
	return 'jpeg image';
}

function imagecreatefrompng($filename)
{
	return 'png image';
}

function imagecreatefromgif($filename)
{
	return 'gif image';
}

function imagealphablending($base_image, $blend_mode)
{
	if($blend_mode)
	{
		assert(false, "blend mode should be false");
	}
}

function imagesavealpha($base_image, $save_flag)
{
	if(!$save_flag)
	{
		assert(false, "save flag should be true");
	}
}
