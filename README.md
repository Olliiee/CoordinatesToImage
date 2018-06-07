# Vatmm CoordinatesToImage
A simple PHP lib to draw real world coordinates on a 2D satellite image.
I wrote this lib to create images like this:
https://vatmm.org/static/map/fullsize/12988

This image based on flight position reports of the VATSIM (https://www.vatsim.net/) International Online Flying Network.

This tool calculates how many degrees are one single pixel of the base image. Depending on this information it paints the coordinates as line on this image.

## Keep in mind
This is not the original code of my script. My script includes loading flight positions from the database and processing this data.

# License
This software is distributed under the LGPL 3.0 license. Please read LICENSE for information on the software availability and distribution.

# What dou you need
- You need a 2D image
- Coordinates to paint a line

# Example
Depending on the altitude the color differs a bit, to create a smooth color change.

```php
<?php

namespace Vatmm\Demo;

use Vatmm\Lib\Image\Baseimage;
use Vatmm\Lib\Image\ImageLib;

require '../src/ImageLib.php';
require '../src/Baseimage.php';

// Get some positions.
$positions = array(
    array("latitude" => 70.5, "longitude" => -22.12, "altitude" => 0),
    array("latitude" => 68.4334, "longitude" => -14.456, "altitude" => 25000),
    array("latitude" => 62.515, "longitude" => -6.64, "altitude" => 30000),
    array("latitude" => 58.436, "longitude" => 0.64, "altitude" => 30000),
    array("latitude" => 52.887, "longitude" => 6.12, "altitude" => 30000),
    array("latitude" => 46.445, "longitude" => 12.12, "altitude" => 35000),
    array("latitude" => 44.225, "longitude" => 19.12, "altitude" => 28000),
    array("latitude" => 38.158, "longitude" => 23.12, "altitude" => 12000),
    array("latitude" => 34.357, "longitude" => 28.12, "altitude" => 0),
);

// Create the Baseimge object.
$baseImage = new Baseimage();

// The size of your image.
$baseImage->setWidth(2048);
$baseImage->setHeight(1366);

// Get the real world coordinates of a point on your 2D image.
// This must be a point on top left corner.
$baseImage->setLeftLatitude(70.2719);
$baseImage->setLeftLongitude(-22.5644);
$baseImage->setLeftxLongitude(86);
$baseImage->setLeftyLatitude(268);

// Get the real world coordinates of a point on your 2D image.
// This must be a point on bottom right corner.
$baseImage->setRightLatitude(29.4368);
$baseImage->setRightLongitude(47.9758);
$baseImage->setRightxLongitude(1820);
$baseImage->setRightyLatitude(1281);

// Load the base image.
$demoImage = imagecreatefromjpeg("demo.jpg");

// Select the line style.
$lineColor = 5;
$alpha = 1;

// Process the image.
$imageLib = new ImageLib();
$newImage = $imageLib->drawPath(
    $positions,
    $demoImage,
    $baseImage,
    $lineColor,
    $alpha
);

// Save it.
imagejpeg($newImage, "result.jpg", 100);
```
 
 That's it.