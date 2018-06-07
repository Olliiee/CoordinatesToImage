<?php
/**
 * This is the main file of the PixelToImage lib.
 *
 * PHP version 7
 *
 * @author  Oliver Strauss <oliver.strauss@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @link    https://vatmm.org
 */
namespace Vatmm\Lib\Image;

use Vatmm\Lib\Image\Baseimage;

/**
 * PixelToImage - The main image lib.
 *
 * This class draws the pixel on the source image.
 *
 * @author Oliver Strauss <oliver.strauss@gmail.com>
 */
class ImageLib
{
    /**
     * A simple line constant we need to
     * define to calculate the best line size.
     *
     * @var float
     */
    const LINECONST = 0.0000006064;

    /**
     * A simple alpha const we need this
     * later to calculate the alpha chanel.
     *
     * @var float
     */
    const ALPHACONST = 0.0000181283;

    const MAPPERSTEPS = 500;

    /**
     * A line color style
     *
     * @var array
     */
    const GRADIENTS_BRIGHT = array('FF3118', 'fdff89', '9fff79', 'e1aeff', 'ff62e5', 'a2dfff');

    /**
     * A line color style
     *
     * @var array
     */
    const GRADIENTS_NORMAL = array('FF3118', 'e1e54b', 'B1AF4B', '9a1de2', '4df924', '58b9f1');

    /**
     * A line color style
     *
     * @var array
     */
    const GRADIENTS_RED = array('FF3118', 'ff4e18', 'ff8018', 'ffae18', 'fff018', 'e8ff18');

    /**
     * A line color style
     *
     * @var array
     */
    const GRADIENTS_GREEN = array('FF3118', 'ffc118', 'fffb18', 'baff18', '18ffc5', '67dcff');

    /**
     * A line color style
     *
     * @var array
     */
    const GRADIENTS_BLUE = array('FF3118', 'ffc118', '18ffba', '18b6ff', '183bff', '7667ff');

    /**
     * A line color style
     *
     * @var array
     */
    const GRADIENTS_XMAS = array('18ff5d', 'a6ff18', 'ffc118', 'ff4618', 'ff1818', 'ff0000');

    /**
     * Draw the position reports on the image.
     *
     * @param array     $positionCoordinates The real world coordinates as
     *                                       latitude, longitude, altitude array.
     * @param resource  $image               The image resource we would like to
     *                                       draw on.
     * @param Baseimage $baseImage           The Baseimage object.
     * @param int       $lineColor           The line color.
     *
     * @return resource $image Returns the new image.
     */
    function drawPath(
        $positionCoordinates,
        $image,
        $baseImage,
        $lineColor = 0,
        $alpha = 0
    ) {
        // Init the pixels. We need them later to draw
        // a long line.
        $lastLat = 0;
        $lastLng = 0;

        // Get the image data.
        $imageData = $this->_getPixelToCoordinate($baseImage);

        // If alpha is not set.
        if ($alpha == 0) {
            $alpha = $this->_setAlpha(count($positionCoordinates), $baseImage);
        }

        $lineSize = $this->_setLineSize($baseImage);

        // If gradients = 0 then use a random color.
        if ($lineColor == 0) {
            // Get the gradients.
            $rand = rand(1, 6);
            $gradients = $this->_setgradients($rand);
        } else {
            $gradients = $this->_setgradients($lineColor);
        }

        foreach ($positionCoordinates as $positionKey => $positionValue) {
            // Get the altitude color.
            if ($positionValue["altitude"] >= 0) {
                $index = round(round(($positionValue["altitude"] * 2), -2, PHP_ROUND_HALF_UP) / self::MAPPERSTEPS);
                if ($index > count($gradients) - 1) {
                    list($r, $g, $b) = sscanf($gradients[count($gradients) - 1], "%02x%02x%02x");
                } elseif ($index < 0) {
                    list($r, $g, $b) = sscanf($gradients[0], "%02x%02x%02x");
                } else {
                    list($r, $g, $b) = sscanf($gradients[$index], "%02x%02x%02x");
                }
                $lineColor = imagecolorallocatealpha($image, $r, $g, $b, $alpha);
            } else {
                $lineColor = imagecolorallocatealpha($image, 255, 255, 255, $alpha);
            }

            // Get the pixel position out of a coordinate.
            $latPixel = $this->_posToPixel(
                $positionValue["latitude"],
                "lat",
                $imageData
            );

            $lngPixel = $this->_posToPixel(
                $positionValue["longitude"],
                "lng",
                $imageData
            );

            if ($lastLat == 0 && $lastLng == 0 && $latPixel > 0 && $lngPixel > 0) {
                // Draw the first point of a line as a dot only. We don't know
                // where to go next and you don't see this single dot.
                imageline(
                    $image,
                    $lngPixel,
                    $latPixel,
                    $lngPixel,
                    $latPixel,
                    $lineColor
                );

                // Save the pixel for the next step.
                $lastLat = $latPixel;
                $lastLng = $lngPixel;
            } else {
                // Draw the line only, if it's on the image.
                if ($latPixel > 0
                    && $lngPixel > 0
                    && $latPixel < $baseImage->getHeight()
                    && $lngPixel < $baseImage->getWidth()
                ) {
                    imageline(
                        $image,
                        $lastLng,
                        $lastLat,
                        $lngPixel,
                        $latPixel,
                        $lineColor
                    );
                }

                // Save the position for the next iteration.
                $lastLat = $latPixel;
                $lastLng = $lngPixel;
            }
        }

        return $image;
    }

    /**
     * Calculating pixel position of a coordinate.
     *
     * @param float  $coordinates The coordinates as latitude or longitude.
     * @param string $type        If the give coordinate is latitude or longitude.
     * @param array  $imageData   The image data.
     *
     * @return int Returns the x/y coordinate as int.
     */
    function _posToPixel($coordinates, $type, $imageData)
    {
        switch ($type) {
            case 'lat':
                $diffDeg = $imageData["nullPointLat"] - $coordinates;
                $pixels = $diffDeg / $imageData["onePixelPerLat"];
                if ($pixels < 0) {
                    return 0;
                }
                return round($pixels, 0);
                break;

            case 'lng':
                $diffDeg = $coordinates - $imageData["nullPointLng"];
                $pixels = $diffDeg / $imageData["onePixelPerLng"];
                if ($pixels < 0) {
                    return 0;
                }
                return round($pixels, 0);
                break;
        }
    }

    /**
     * What is a pixel in degrees?
     *
     * Depending on the image size get the degrees in pixel and
     * the coordinates of the top left corner of the image.
     *
     * @param Image $image The image object contains all data we need here.
     *
     * @return array Returns the calculated data.
     */
    function _getPixelToCoordinate(Baseimage $image)
    {
        $latCooDif = $image->getRightlatitude() - $image->getLeftlatitude();
        $latPixelDif = $image->getLeftylatitude() - $image->getRightylatitude();

        // How many degrees as latitude is one pixel on the image?
        $onePixelPerLat = $latCooDif / $latPixelDif;

        $lngCooDif = $image->getLeftlongitude() - $image->getRightlongitude();
        $lngPixelDif = $image->getLeftxlongitude() - $image->getRightxlongitude();

        // How many degrees as longitude is one pixel on the image?
        $onePixelPerLng = $lngCooDif / $lngPixelDif;

        // Get the latitude und longitude of the top left corner. (0/0)
        $nullPointLat = $image->getLeftlatitude() + ($image->getLeftylatitude() * $onePixelPerLat);
        $nullPointLng = $image->getLeftlongitude() - ($image->getLeftxlongitude() * $onePixelPerLng);

        return array(
            'onePixelPerLat' => $onePixelPerLat,
            'onePixelPerLng' => $onePixelPerLng,
            'nullPointLat' => $nullPointLat,
            'nullPointLng' => $nullPointLng,
        );
    }

    /**
     * Get the best line size for this image.
     *
     * @param Baseimage $image The Baseimage object with all necessary data.
     *
     * @return int The line size as int.
     */
    function _setLineSize(Baseimage $image)
    {
        // Depending on the image size, calculate the line size.
        $pixels = ($image->getWidth() * $image->getHeight());
        $lineSize = round(($pixels * self::LINECONST), 0);

        if ($lineSize == 0) {
            $lineSize = 1;
        } elseif ($lineSize > 3) {
            // It looks like 3 is the max value.
            $lineSize = 3;
        }

        return $lineSize;
    }

    /**
     * Define the alpha chanel.
     *
     * @param int       $total Total count of objects.
     * @param Baseimage $image The Baseimage object.
     *
     * @return float $alpha Returns the alpha for this image.
     */
    function _setAlpha($total, $image)
    {
        $pixels = ($image->getWidth() * $image->getHeight());
        $alphasPerPixel = round(($pixels * self::ALPHACONST), 0);

        // Create the alpha chanel.
        $alpha = 110 - round($alphasPerPixel / 110, 0);

        // 40 is the minimum.
        if ($alpha < 40) {
            $alpha = 40;
        }

        return $alpha;
    }

    /**
     * Create the gradient list.
     *
     * @param int $gradientsStyle Create a gradient style.
     *
     * @return array Returns an array.
     */
    function _setGradients($gradientsStyle)
    {
        switch ($gradientsStyle) {
            case 1:
                $style = self::GRADIENTS_NORMAL;
                break;

            case 2:
                $style = self::GRADIENTS_BRIGHT;
                break;

            case 3:
                $style = self::GRADIENTS_RED;
                break;

            case 4:
                $style = self::GRADIENTS_GREEN;
                break;

            case 5:
                $style = self::GRADIENTS_BLUE;
                break;

            case 6:
                $style = self::GRADIENTS_XMAS;
                break;

            default:
                $style = self::GRADIENTS_NORMAL;
                break;
        }

        $gradients1 = $this->_generateGradient($style[0], $style[1], round(20000 / self::MAPPERSTEPS));
        array_pop($gradients1);
        $gradients2 = $this->_generateGradient($style[1], $style[2], round(20000 / self::MAPPERSTEPS));
        array_pop($gradients2);
        $gradients3 = $this->_generateGradient($style[2], $style[3], round(20000 / self::MAPPERSTEPS));
        array_pop($gradients3);
        $gradients4 = $this->_generateGradient($style[3], $style[4], round(20000 / self::MAPPERSTEPS));
        array_pop($gradients4);
        $gradients5 = $this->_generateGradient($style[4], $style[5], round(20000 / self::MAPPERSTEPS));
        array_pop($gradients5);
        $gradients = array_merge($gradients1, $gradients2, $gradients3, $gradients4, $gradients5);

        return $gradients;
    }

    /**
     * Create a gradient depending on the start and end point.
     *
     * @param string $hexFrom    The first color as hex.
     * @param string $hexTo      The last color as hex.
     * @param int    $colorSteps The color steps between start and stop.
     *
     * @return array(string) with the gradients colors.
     */
    function _generateGradient($hexFrom, $hexTo, $colorSteps)
    {
        $fromrgb['r'] = hexdec(substr($hexFrom, 0, 2));
        $fromrgb['g'] = hexdec(substr($hexFrom, 2, 2));
        $fromrgb['b'] = hexdec(substr($hexFrom, 4, 2));

        $torgb['r'] = hexdec(substr($hexTo, 0, 2));
        $torgb['g'] = hexdec(substr($hexTo, 2, 2));
        $torgb['b'] = hexdec(substr($hexTo, 4, 2));

        $steprgb['r'] = ($fromrgb['r'] - $torgb['r']) / ($colorSteps - 1);
        $steprgb['g'] = ($fromrgb['g'] - $torgb['g']) / ($colorSteps - 1);
        $steprgb['b'] = ($fromrgb['b'] - $torgb['b']) / ($colorSteps - 1);

        $gradientColors = array();

        for ($i = 0; $i <= $colorSteps; $i++) {
            $rgb['r'] = floor($fromrgb['r'] - ($steprgb['r'] * $i));
            $rgb['g'] = floor($fromrgb['g'] - ($steprgb['g'] * $i));
            $rgb['b'] = floor($fromrgb['b'] - ($steprgb['b'] * $i));

            $hexRgb['r'] = sprintf('%02x', ($rgb['r']));
            $hexRgb['g'] = sprintf('%02x', ($rgb['g']));
            $hexRgb['b'] = sprintf('%02x', ($rgb['b']));

            $gradientColors[] = implode(null, $hexRgb);
        }

        return $gradientColors;
    }
}
