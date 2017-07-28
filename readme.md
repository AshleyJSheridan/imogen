# Imogen - PHP Image Generator

This is an image generator written in PHP aimed at generating images on the fly upon request.

## What Is Imogen?

Historically, once emails were sent, they were fixed with that content forever. They could only ever be as dynamic as the content that was added when the email campaign was sent.

Imogen changes this. Imogen allows you to include images which aren't actually generated until your recipient opens the email!

Imagine, now you can have emails that respond to the weather, update maps according to the users location, or include your latest Tweet!

### Requirements

* PHP 5.3+
* GD
* Image Magick (Magick Wand not required)
* MySQL for some advanced features but not required if features are not used

### Installation Instructions

* Clone repo
* Run `composer install` from within the project directory - this requires that [Composer](https://getcomposer.org/) is installed locally and in your system `path`
* Point your web server at the `index.php` file in the `public` directory

## Configuring and Using

Each image maps to a configuration file in the `config/campaigns` directory, and takes the format of a returnable PHP array. Name these however makes the most sense to you.

In the top level there are only a few required parameters, depending on how the base is generated:

Building an image using an existing one as a base; this allows it to automatically set the dimensions of the image:
```PHP
<?php
return [
	'type'        => 'flat',
	'description' => 'personalised image with name and seat details',
	'base'        => 'train/train_base.jpg',
	'overlays'    => [],
];
```

Building an image from a blank coloured canvas, specifying the width and height required:
```php
<?php
return [
	'type'        => 'flat',
	'description' => 'personalised image with name and seat details',
	'width'       => 600,
	'height'      => 350,
	'background'  => '#fff',
	'overlays'    => [],
];
```

## Overlays

Think of an overlay like a layer in Photoshop. Each image will be made up of one or more overlays, containing text, other images, or other dynamic content.

Each overlay is an array element within the `overlays` array in the config. Depending on the overlay being used will determine the parameters required. You can omit a parameter specific to an overal as long as you include it in the main config layer as a fallback option. This lets you, for example, give a single text colour once, without having to specifiy the same colour repeatedly in each text overlay.

If you specify placeholders in overlays, like `{{param}}`, then the corresponding request parameter value will be used if a matching one is found. If a matching parameter is not found, the placeholder will be replaced with an empty string.

### Text Overlays
Parameter | Example | Description
--- | --- | ---
`type`|`"text"`|The type of overlay
`content`|`"hello {{name}}"`|The text to display. Anything inside double curly braces will be substituted for a request parameter of the same key
`font`|`"path/to/font.otf"`|A path to the font, see below for specifying resource files
`size`|`18`|The font size to use for this text
`min_size`|14|The minimum font size that this text can be scaled down to if it doesn't fit at the preferred size
`x1`,`y1`,`x2`,`y2`|`10`|These four specify the coordinates for the bounding box in which to place the text. Coordinates are based on the base image dimensions.

### Image Overlays
Parameter | Example | Description
--- | --- | ---
`type`|`"image"`|The type of overlay
`url`|`"face-smile-big.png"`|A path to the image, see below for specifying resource files
`x`|50|The pixel coordinate for the left edge of the placed image
`y`|50|The pixel coordinate for the top edge of the placed image
`angle`|45|The angle (in degrees) of the placed image. If the rotation isn't divisible by 90, the overlay will be larger than the source image, so it's worth bearing in mind

### Google Maps Overlays
Parameter | Example | Description
--- | --- | ---
`type`|`"googlemap"`|The type of overlay
`api_key`|`"some api key string"`|The API key to use. If an array of keys is supplied, one will be picked at random
`center`|`"{{postcode}}"`|The location to use to center the map on, can be anything that Google accepts as a search parameter
`zoom`|10|The zoom level to use, larger values mean a higher zoom level
`source_width`|640|The width of the map image to fetch from Googles API
`source_height`|480|The height of the map image to fetch from Googles API
`markers`|`"{{postcode}}"`|A location of where to place a marker on the map. This can be an array of markers so you can place multiple ones all over the map
`markers_colour`|`#0070b0`|A colour to use for the marker(s)
`x`|50|The x coordinate to place the map image at
`y`|50|The y coordinate to place the map image at
`dest_width`|320|The width of theplaced map, which should be the same or smaller than the `source_width`
`dest_height`|320|The width of theplaced map, which should be the same or smaller than the `source_height`

### Resource Asset Paths
Some of your config values will make reference to external resources, such as images, fonts, etc. If the path is local, these resources will be relative from the `source_assets` directory in the root of the project. If the resource is an external asset, then the full protocal is required.

## License

Imogen is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).


The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).




