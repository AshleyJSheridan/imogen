<?php

namespace App\Helpers;

use Illuminate\Http\Request as Request;
use Illuminate\Filesystem\Filesystem as Filesystem;

/**
 * Description of CacheFileHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CacheFileHelper
{
	private $request;
	private $filesystem;
	
	public function __construct(Request $request, Filesystem $filesystem)
	{
		$this->request = $request;
		$this->filesystem = $filesystem;
	}
	
	public function get_output_filename($campaign_name, $output_format, $record_id)
	{
		$assets_dir = $this->create_output_directory($campaign_name);
		$extension = $this->get_file_extension($output_format);
		
		if(!empty($record_id) )
			$basename = $this->get_hashed_filename ($record_id);
		else
			$basename = $this->get_output_base_filename_as_string();
			
		$filename = "$assets_dir/$basename.$extension";
		
		return $filename;
	}
	
	public function local_cache_exists($filename)
	{
		return file_exists($filename);
	}
	
	public function local_cache_file_recent($filename, $cache_duration)
	{
		if(!$this->local_cache_exists($filename) )
		{
			return false;
		}
		else
		{
			try
			{
				$cache_file_timestamp = $this->filesystem->lastModified($filename);
			}
			catch (Exception $e)
			{
				return false;
			}
			
			return !((time() - $cache_duration) > $cache_file_timestamp);
		}
	}
	
	public function get_cache_duration($duration)
	{
		if(preg_match_all('/(?:([\d]+)([smhdw]))+/', $duration, $matches) && count($matches[1]) )
		{
			$cache_duration = 0;
			
			for($i = 0; $i < count($matches[1]); $i++)
			{
				
				$duration_multiplier = $this->get_duration_multiplier($matches[2][$i]);
				$duration_amount = floatval($matches[1][$i]);
				
				$expires_seconds = $duration_amount * $duration_multiplier;
				
				$cache_duration += $expires_seconds;
			}
			
			return $cache_duration;
		}
		
		return false;
	}
	
	private function get_duration_multiplier($unit_type)
	{
		$multiplier = 1;
		
		switch($unit_type)
		{
			case 'm':
				$multiplier = 60;
				break;
			case 'h':
				$multiplier = 60 * 60;
				break;
			case 'd':
				$multiplier = 60 * 60 * 24;
				break;
			case 'w':
				$multiplier = 60 * 60 * 24 * 7;
				break;
		}
		return $multiplier;
	}
	
	private function get_duration_multiplier_human($unit_type)
	{
		$multiplier = 'seconds';
		
		switch($unit_type)
		{
			case 'm':
				$multiplier = 'minutes';
				break;
			case 'h':
				$multiplier = 'hours';
				break;
			case 'd':
				$multiplier = 'days';
				break;
			case 'w':
				$multiplier = 'weeks';
				break;
		}
		return $multiplier;
	}
	
	private function get_file_extension($output_format)
	{
		switch($output_format)
		{
			case 'gif':
			case 'png':
				return $output_format;
				break;
			default:
				return 'jpg';
				break;
		}
	}
	
	private function create_output_directory($campaign_name)
	{
		$assets_dir_path = getcwd() . '/../dist_assets/';
		$assets_dir = realpath($assets_dir_path);
		$campaign_assets_dir = "$assets_dir/$campaign_name";
		
		if(!$assets_dir)
			throw new \App\Exceptions\DirectoryMissingException($assets_dir_path);
		
		if(!file_exists($campaign_assets_dir) )
		{
			if(!mkdir($campaign_assets_dir, 0777, true) )
				throw new \App\Exceptions\CreateDirectoryException("Could not create $campaign_assets_dir");
		}

		return $campaign_assets_dir;
	}
	
	private function get_output_base_filename_as_string()
	{
		$campaign_path = $this->request->path();
		$query_string = $this->request->getQueryString();
		
		$request_string = "{$campaign_path}_{$query_string}";
		$hashed_filename = $this->get_hashed_filename($request_string);
		
		return $hashed_filename;
	}
	
	private function get_hashed_filename($filename)
	{
		return md5($filename);
	}
}
