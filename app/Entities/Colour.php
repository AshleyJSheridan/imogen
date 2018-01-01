<?php

namespace App\Entities;

/**
 * Description of Colour
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class Colour
{
	private $alpha = 0;
	private $r = 0;
	private $g = 0;
	private $b = 0;
		
	public function __construct($colour)
	{		
		$clean_colour = $this->remove_hash($colour);
		$this->extract_colour_values($clean_colour);
	}
	
	public function __toString()
	{
		return $this->get_string();
	}
	
	public function get_string()
	{
		$r = str_pad(dechex($this->r), 2, '0', STR_PAD_LEFT);
		$g = str_pad(dechex($this->g), 2, '0', STR_PAD_LEFT);
		$b = str_pad(dechex($this->b), 2, '0', STR_PAD_LEFT);
		$alpha = str_pad(dechex($this->alpha), 2, '0', STR_PAD_LEFT);

		if($this->alpha)
		{
			return $r . $g . $b . $alpha;
		}
		else
		{
			return $r . $g . $b;
		}
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
	
	private function extract_colour_values($colour)
	{
		$colour_hex_length = strlen($colour);

		// extract the alpha value for 4 and 8 character strings
		// normalise short hex #rgb into long hex #rrggbb format
		// and then extract the rgb from the 6 character string left over
		switch($colour_hex_length)
		{
			case 3:
				$colour = preg_replace('/^([0-9a-z])([0-9a-z])([0-9a-z])$/', '$1$1$2$2$3$3', $colour);
				break;
			case 4:
				$this->alpha = intval(hexdec(substr($colour, -1).substr($colour, -1) ) );
				$colour = preg_replace('/^([0-9a-z])([0-9a-z])([0-9a-z])([0-9a-z])$/', '$1$1$2$2$3$3', $colour);
				break;
			case 8:
				$this->alpha = intval(hexdec(substr($colour, -2) ) );
				$colour = substr($colour, 0, 6);
				break;
			default:
				throw new \App\Exceptions\InvalidColourHexException("Invalid colour $colour used");
			case 6:
				break;
		}
		
		$this->extract_rgb_from_6_char_hex($colour);

		return $colour;
	}
	
	private function extract_rgb_from_6_char_hex($hex)
	{
		list($this->r, $this->g, $this->b) = sscanf($hex, "%02x%02x%02x");
	}
	
	private function remove_hash($colour)
	{
		return str_replace('#', '', $colour);
	}
}
