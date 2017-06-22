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

### Text Overlays
Parameter | Example | Description
--- | --- | ---
`type`|`"text"`|The type of overlay
`content`|`"hello {{name}}"`|The text to display. Anything inside double curly braces will be substituted for a request parameter of the same key
`font`|`"path/to/font.otf"`|A path to the font, see below for specifying resource files
`size`|`18`|The font size to use for this text
`min_size`|14|The minimum font size that this text can be scaled down to if it doesn't fit at the preferred size
`x1`,`y1`,`x2`,`y2`|`10`|These four specify the coordinates for the bounding box in which to place the text. Coordinates are based on the base image dimensions.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).




