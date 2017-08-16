<?php

namespace App\Renderers;

use App\Helpers\ConfigHelper as ConfigHelper;
use App\Helpers\CacheFileHelper as CacheFileHelper;
use App\Renderers\iRenderer as iRenderer;

/**
 * Description of RenderFactory
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 * @return iRenderer;
 */
class RenderFactory
{
	private $config_helper;
	private $cache_file_helper;
	
	public function __construct(ConfigHelper $config_helper, CacheFileHelper $cache_file_helper)
	{
		$this->config_helper = $config_helper;
		$this->cache_file_helper = $cache_file_helper;
	}
	
	public function create()
	{
		$class_name = $this->get_renderer_class_name();
		
		if(!class_exists($class_name) )
			throw new \App\Exceptions\UnsupportedImageRendererException;
		else
			$class = new $class_name($this->config_helper, $this->cache_file_helper);
		
		return $class;
	}
	
	private function get_renderer_class_name()
	{
		$type = $this->config_helper->get('type');
		
		return 'App\\Renderers\\' . ucfirst($type) . 'Renderer';
	}
}
