<?php
return array(
	'type'			=> 'flat',
	'description'	=> '',
	'format'		=> 'png',
	'width'			=> 640,
	'height'		=> 480,
	'background'	=> '#00b070',
	'overlays'    => array(
		'map' => array(
			'type'				=> 'googlemap',
			'api_key'		=> array(
				'AIzaSyBNvH6ZCGAzeIk2zNe2uqZ6LP4xxrJ7Nn8',
			),
			'center'		=> '{{postcode}}',
			'zoom'			=> 12,
			'source_width'	=> 640,
			'source_height'	=> 480,
			'markers'		=> array(
				'{{postcode}}',
				'British Museum, London, UK',
				'Victoria and Albert Museum, London, UK'
			),
			'markers_colour'	=> '#0070b0',
			'x'				=> 160,
			'y'				=> 120,
			'dest_width'	=> 320,
			'dest_height'	=> 240,
		),
	),
);
