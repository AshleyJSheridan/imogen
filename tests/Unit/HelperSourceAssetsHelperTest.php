<?php

namespace App\Helpers;

use Tests\TestCase;
use App\Helpers\FileMimeHelper as FileMimeHelper;
use App\Helpers\SourceAssetsHelper as SourceAssetsHelper;
use App\Helpers\iPathHelper as iPathHelper;

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
