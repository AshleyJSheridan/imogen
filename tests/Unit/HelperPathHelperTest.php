<?php
namespace App\Helpers;

use Tests\TestCase;

class HelperPathHelperTest extends TestCase
{
	private $helper;
	
	protected function setUp()
	{
		$this->helper = new PathHelper();
	}
	
	public function testSourceAssetsHelperConstruction()
	{
		$this->assertTrue($this->helper instanceof PathHelper);
	}
	
	public function testGetRealPath()
	{
		$original_path = 'some possible real path';
		
		$path = $this->helper->get_real_path($original_path);
		
		$this->assertEquals($path, 'some real path');
	}
	
	public function testGetCurrentWorkingDirectory()
	{
		$path = $this->helper->get_current_working_directory();
		
		$this->assertEquals($path, 'some current path');
	}
}

function realpath($path)
{
	return 'some real path';
}

function getcwd()
{
	return 'some current path';
}