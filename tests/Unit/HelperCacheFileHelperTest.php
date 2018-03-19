<?php

namespace App\Helpers;

use Tests\TestCase;
use Illuminate\Filesystem\Filesystem as Filesystem;

class HelperCacheFileHelperTest extends TestCase
{
	private $helper;
	private $request;
	private $filesystem;
	
	protected function setUp()
	{
		$this->request = new MockRequest();
		$this->filesystem = new MockFilesystem();
		
		$this->helper = new CacheFileHelper($this->request, $this->filesystem);
	}
	
	public function testCacheFileHelperConstruction()
	{
		$this->assertTrue($this->helper instanceof CacheFileHelper);
	}
	
	public function testGetOutputFilenameWithRecordId()
	{
		$campaign_name = 'some name';
		$output_format = 'some format';
		$record_id = 123;
		$dir = 'some dir';
		$extension = 'some extension';
		$hashed_filename = 'some hashed filename';
		$expected_filename = "$dir/$hashed_filename.$extension";
		
		$mock_helper = $this->getMockBuilder(CacheFileHelper::class)
			->setMethods(['create_output_directory', 'get_file_extension', 'get_hashed_filename', '__construct'])
			->disableOriginalConstructor()
			->getMock();
		
		$mock_helper->method('create_output_directory')
			->willReturn($dir);
		$mock_helper->method('get_file_extension')
			->willReturn($extension);
		$mock_helper->method('get_hashed_filename')
			->willReturn($hashed_filename);
		
		$output_filename = $mock_helper->get_output_filename($campaign_name, $output_format, $record_id);
		
		$this->assertEquals($output_filename, $expected_filename);
	}
	
	public function testGetOutputFilenameWithoutRecordId()
	{
		$campaign_name = 'some name';
		$output_format = 'some format';
		$record_id = '';
		$dir = 'some dir';
		$extension = 'some extension';
		$filename = 'some filename';
		$expected_filename = "$dir/$filename.$extension";
		
		$mock_helper = $this->getMockBuilder(CacheFileHelper::class)
			->setMethods(['create_output_directory', 'get_file_extension', 'get_output_base_filename_as_string', '__construct'])
			->disableOriginalConstructor()
			->getMock();
		
		$mock_helper->method('create_output_directory')
			->willReturn($dir);
		$mock_helper->method('get_file_extension')
			->willReturn($extension);
		$mock_helper->method('get_output_base_filename_as_string')
			->willReturn($filename);
		
		$output_filename = $mock_helper->get_output_filename($campaign_name, $output_format, $record_id);
		
		$this->assertEquals($output_filename, $expected_filename);
	}
	
	public function testLocalCacheExists()
	{
		$local_filename = 'existing file';
		
		$file_exists = $this->helper->local_cache_exists($local_filename);
		
		$this->assertEquals($file_exists, true);
	}
	
	public function testLocalCacheDoesNotExist()
	{
		$local_filename = 'non existing file';
		
		$file_exists = $this->helper->local_cache_exists($local_filename);
		
		$this->assertEquals($file_exists, false);
	}
	
	public function testLocalCacheFileRecentWhenFileDoesNotExist()
	{
		$filename = 'non existing file';
		$cache_duration = 'some duration';
		
		$is_recent = $this->helper->local_cache_file_recent($filename, $cache_duration);
		
		$this->assertEquals($is_recent, false);
	}
	
	public function testLocalCacheFileRecentWhenFileIsRecent()
	{
		$filename = 'existing file';
		$cache_duration = 1000;
		$last_modified = 2000;

		$mock_filesystem = new MockFilesystem($last_modified);
		
		$mock_helper = $this->getMockBuilder(CacheFileHelper::class)
			->setMethods(['local_cache_exists'])
			->setConstructorArgs([$this->request, $mock_filesystem])
			->getMock();
		
		$mock_helper->method('local_cache_exists')
			->willReturn(true);
		
		$is_recent = $mock_helper->local_cache_file_recent($filename, $cache_duration);
		
		$this->assertFalse($is_recent);
	}
	
	public function testLocalCacheFileRecentWhenFileIsNotRecent()
	{
		$filename = 'existing file';
		$cache_duration = 1000;
		$last_modified = 4000;

		$mock_filesystem = new MockFilesystem($last_modified);
		
		$mock_helper = $this->getMockBuilder(CacheFileHelper::class)
			->setMethods(['local_cache_exists'])
			->setConstructorArgs([$this->request, $mock_filesystem])
			->getMock();
		
		$mock_helper->method('local_cache_exists')
			->willReturn(true);
		
		$is_recent = $mock_helper->local_cache_file_recent($filename, $cache_duration);
		
		$this->assertTrue($is_recent);
	}
	
	public function testCacheDurationInvalidFormat()
	{
		$duration = 'invalid format';
		
		$cache_duration = $this->helper->get_cache_duration($duration);
		
		$this->assertFalse($cache_duration);
	}
	
	public function testCacheDurationWith30Seconds()
	{
		$duration = '30s';
		
		$cache_duration = $this->helper->get_cache_duration($duration);
		
		$this->assertEquals($cache_duration, 30);
	}
	
	public function testCacheDurationWith5Minutes30Seconds()
	{
		$duration = '5m30s';
		
		$cache_duration = $this->helper->get_cache_duration($duration);

		$this->assertEquals($cache_duration, 5 * 60 + 30);
	}
	
	public function testCacheDurationWith2Hours20Minutes10Seconds()
	{
		$duration = '2h20m10s';
		
		$cache_duration = $this->helper->get_cache_duration($duration);

		$this->assertEquals($cache_duration, 2 * 60 * 60 + 20 * 60 + 10);
	}
	
	public function testCacheDurationWith4Days3Hours40Minutes5Seconds()
	{
		$duration = '4d3h40m5s';
		
		$cache_duration = $this->helper->get_cache_duration($duration);

		$this->assertEquals($cache_duration, 4 * 24 * 60 * 60 + 3 * 60 * 60 + 40 * 60 + 5);
	}
	
	public function testCacheDurationWith2Weeks1Day1Hour10Minutes10Seconds()
	{
		$duration = '2w1d1h10m10s';
		
		$cache_duration = $this->helper->get_cache_duration($duration);

		$this->assertEquals($cache_duration, 2 * 7 * 24 * 60 * 60 + 1 * 24 * 60 * 60 + 1 * 60 * 60 + 10 * 60 + 10);
	}
}

class MockFilesystem extends FileSystem
{
	private $modified_time;
	
	public function __construct($modified_time=null)
	{
		$this->modified_time = $modified_time;
	}
	
	public function lastModified($modified_time)
	{
		return $this->modified_time;
	}
}

function file_exists($filename)
{
	return ($filename == 'existing file');
}

function time()
{
	return 4000;
}
