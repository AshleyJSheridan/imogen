<?php

namespace App\Entities;

use App\Entities\ImageLayer as ImageLayer;

/**
 * Description of Image
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class Image implements \ArrayAccess
{
	private $image_layers = [];
	
	public function offsetExists($offset)
	{
		return isset($this->container[$offset]);
	}
	
	public function offsetGet($offset)
	{
		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
	
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
			$this->container[] = $value;
		else
			$this->container[$offset] = $value;
    }
	
	public function offsetUnset($offset)
	{
		unset($this->container[$offset]);
	}
	
	public function add_layer(ImageLayer $image_layer)
	{
		$this->image_layers[] = $image_layer;
	}
}
