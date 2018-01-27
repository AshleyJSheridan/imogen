<?php

namespace App\Helpers;

use Tests\TestCase;

class HelperFileMimeHelperTest extends TestCase
{
	private $helper;
	
	protected function setUp()
	{
		$this->helper = new FileMimeHelper();
	}
	
	public function testSourceAssetsHelperConstruction()
	{
		$this->assertTrue($this->helper instanceof FileMimeHelper);
	}
	
	public function testUnrecognisedImageTypeGettingMimeFromFilename()
	{
		$filename = 'test.doc';
		
		$this->expectException(\App\Exceptions\UnrecognisedImageType::class);
		
		$mime = $this->helper->get_mime_type_from_filename($filename);
		
		$this->assertNotEquals($mime, 'image/jpg');
		$this->assertNotEquals($mime, 'image/png');
		$this->assertNotEquals($mime, 'image/gif');
	}
	
	public function testJpgFileGettingMimeFromFilename()
	{
		$filename = 'test.jpg';
		
		$mime = $this->helper->get_mime_type_from_filename($filename);
		
		$this->assertEquals($mime, 'image/jpeg');
	}

	public function testJpegFileGettingMimeFromFilename()
	{
		$filename = 'test.jpeg';
		
		$mime = $this->helper->get_mime_type_from_filename($filename);
		
		$this->assertEquals($mime, 'image/jpeg');
	}
	
	public function testPngFileGettingMimeFromFilename()
	{
		$filename = 'test.png';
		
		$mime = $this->helper->get_mime_type_from_filename($filename);
		
		$this->assertEquals($mime, 'image/png');
	}
	
	public function testGigFileGettingMimeFromFilename()
	{
		$filename = 'test.gif';
		
		$mime = $this->helper->get_mime_type_from_filename($filename);
		
		$this->assertEquals($mime, 'image/gif');
	}
	
	public function testGetMimeFromExtensionWithPng()
	{
		$extension = 'png';
		
		$image_mime = $this->helper->get_mime_from_extension($extension);
		
		$this->assertEquals((string)$image_mime, 'image/png');
	}
	
	public function testGetMimeFromExtensionWithGif()
	{
		$extension = 'gif';
		
		$image_mime = $this->helper->get_mime_from_extension($extension);
		
		$this->assertEquals((string)$image_mime, 'image/gif');
	}
	
	public function testGetMimeFromExtensionWithJpg()
	{
		$extension = 'jpg';
		
		$image_mime = $this->helper->get_mime_from_extension($extension);
		
		$this->assertEquals((string)$image_mime, 'image/jpeg');
	}
	
	public function testGetMimeFromExtensionWithJpeg()
	{
		$extension = 'jpeg';
		
		$image_mime = $this->helper->get_mime_from_extension($extension);
		
		$this->assertEquals((string)$image_mime, 'image/jpeg');
	}
	
	public function testGetMimeFromExtensionWithNonImageExtension()
	{
		$extension = 'doc';
		
		$image_mime = $this->helper->get_mime_from_extension($extension);
		
		$this->assertEquals((string)$image_mime, 'image/jpeg');
	}
}
