<?php

namespace App\Entities;

/**
 * Description of Colour
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class Colour
{
	private $alpha;
	private $r;
	private $g;
	private $b;
		
	public function __construct($colour)
	{
		$colour = $this->normalise_hex_and_extract_alpha($colour);
		
		$rgb = sscanf($colour, '%2x%2x%2x');
		
		$this->r = $rgb[0];
		$this->g = $rgb[1];
		$this->b = $rgb[2];
	}
	
	public function __toString()
	{
		return $this->get_string();
	}
	
	private function normalise_hex_and_extract_alpha($colour)
	{
		$colour_hex_length = strlen($colour);
		
		if($colour_hex_length == 6)
			return $colour;
		else
		{
			switch($colour_hex_length)
			{
				case 3:
					$colour = preg_replace('/^([0-9a-z])([0-9a-z])([0-9a-z])$/', '$1$1$2$2$3$3', $colour);
					break;
				case 4:
					$this->alpha = intval(floor(hexdec(substr($colour, -1).substr($colour, -1) ) / 2 ) );
					$colour = preg_replace('/^([0-9a-z])([0-9a-z])([0-9a-z])([0-9a-z])$/', '$1$1$2$2$3$3', $colour);
					break;
				case 8:
					$this->alpha = intval(floor(hexdec(substr($colour, -2) ) / 2 ) );
					$colour = substr($colour, 0, 6);
					break;
				default:
					throw new \App\Exceptions\InvalidColourHexException("Invalid colour $colour used");
			}
		}
		
		return $colour;
	}
	
	public function get_string()
	{
		return dechex($this->r) . dechex($this->g) . dechex($this->b) . dechex($this->alpha);
	}
	
	public function get_red()
	{
		return $this->r;
	}
	
	public function get_green()
	{
		return $this->g;
	}
	
	public function get_blue()
	{
		return $this->b;
	}
	
	public function get_alpha()
	{
		return $this->alpha;
	}
}
