<?php

namespace App\Entities;

/**
 * Description of ImageTTFBox
 *
 * @author Ashley Sheridan <ash@ashleysheridan.co.uk>
 */
class ImageTTFBox
{
	private $box_width;
	private $box_height;
	private $fits;
	private $baseline_offset;
	
	public function __construct($box_width, $box_height, $fits, $baseline_offset)
	{
		$this->box_width = $box_width;
		$this->box_height = $box_height;
		$this->fits = $fits;
		$this->baseline_offset = $baseline_offset;
	}
	
	public function get_fits()
	{
		return $this->fits;
	}
	
	public function get_box_width()
	{
		return $this->box_width;
	}
	
	public function get_box_height()
	{
		return $this->box_height;
	}
	
	public function get_baseline_offset()
	{
		return $this->baseline_offset;
	}
}
