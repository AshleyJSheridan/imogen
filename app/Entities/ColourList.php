<?php

namespace App\Entities;

use App\Entities\Colour as Colour;

/**
 * Description of ColourList
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ColourList extends \SplDoublyLinkedList
{
	private $colours = [];
	
	public function add_colour(Colour $colour, &$image)
	{
		$colour_string = $colour->get_string();
		
		if($colour->get_alpha() )
			$this->colours[$colour_string] = imagecolorallocatealpha($image, $colour->get_red(), $colour->get_green(), $colour->get_blue(), $colour->get_alpha() );
		else
			$this->colours[$colour_string] = imagecolorallocate($image, $colour->get_red(), $colour->get_green(), $colour->get_blue() );
		
		return $this->colours[$colour_string];
	}
}
