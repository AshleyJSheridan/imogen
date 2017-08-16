<?php

namespace App\Helpers;

/**
 * Description of CacheFileHelper
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class CacheFileHelper
{
	public function get_output_filename($campaign_name, $record_id)
	{
		$this->create_directory($campaign_name);

		
		if(!empty($record_id) )
			$filename = $record_id;
		else
		{
			if(!empty($campaign['filename']) )
				$filename = $campaign['filename'];
			else
				$filename = filter_var(urldecode(implode('_', Request::segments() ) ), FILTER_SANITIZE_STRING);	// there's no unique item to record by, so concatenate the URL params instead
		}
		
		/*$filename = "$assets_dir/$filename";
		
		return $filename;*/
	}
	
	private function create_directory($campaign_name)
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
}
