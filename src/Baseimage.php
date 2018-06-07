<?php
/**
 * This is the Image object file of the PixelToImage lib.
 *
 * PHP version 7
 *
 * @author  Oliver Strauss <oliver.strauss@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version GIT: 1.0
 * @link    https://vatmm.org
 */
namespace Vatmm\Lib\Image;

/**
 * This Image class contains all necessary
 * data to create an image.
 *
 * @author Oliver Strauss <oliver.strauss@gmail.com>
 */
class Baseimage
{
    /**
     * The width of the base image.
     *
     * @var integer
     */
    private $_width;

    /**
     * The height of the base image.
     *
     * @var integer
     */
    private $_height;

    /**
     * A latitude located in the picture on the top left.
     *
     * @var float
     */
    private $_leftLatitude;

    /**
     * A longitude located in the picture on the top left.
     *
     * @var float
     */
    private $_leftLongitude;

    /**
     * This var represents the Y coordinate of the
     * top left latitude.
     *
     * @var integer
     **/
    private $_leftyLatitude;

    /**
     * This var represents the X coordinate of the
     * top left longitude.
     *
     * @var integer
     */
    private $_leftxLongitude;

    /**
     * A latitude located in the picture on the bottom right.
     *
     * @var float
     */
    private $_rightLatitude;

    /**
     * A longitude located in the picture on the bottom right.
     *
     * @var float
     */
    private $_rightLongitude;

    /**
     * This var represents the Y coordinate of the
     * bottom right latitude.
     *
     * @var integer
     */
    private $_rightyLatitude;

    /**
     * This var represents the X coordinate of the
     * bottom right longitude.
     *
     * @var integer
     */
    private $_rightxLongitude;

    /**
     * Get the width of the base image.
     *
     * @return  integer
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * Set the width of the base image.
     *
     * @param  integer  $_width  The width of the base image.
     *
     * @return  self
     */
    public function setWidth($width)
    {
        $this->_width = $width;

        return $this;
    }

    /**
     * Get the height of the base image.
     *
     * @return  integer
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * Set the height of the base image.
     *
     * @param  integer  $_height  The height of the base image.
     *
     * @return  self
     */
    public function setHeight($height)
    {
        $this->_height = $height;

        return $this;
    }

    /**
     * Get a latitude located in the picture on the top left.
     *
     * @return  float
     */
    public function getLeftLatitude()
    {
        return $this->_leftLatitude;
    }

    /**
     * Set a latitude located in the picture on the top left.
     *
     * @param  float  $_leftLatitude  A latitude located in the picture on the top left.
     *
     * @return  self
     */
    public function setLeftLatitude($leftLatitude)
    {
        $this->_leftLatitude = $leftLatitude;

        return $this;
    }

    /**
     * Get a longitude located in the picture on the top left.
     *
     * @return  float
     */
    public function getLeftLongitude()
    {
        return $this->_leftLongitude;
    }

    /**
     * Set a longitude located in the picture on the top left.
     *
     * @param  float  $_leftLongitude  A longitude located in the picture on the top left.
     *
     * @return  self
     */
    public function setLeftLongitude($leftLongitude)
    {
        $this->_leftLongitude = $leftLongitude;

        return $this;
    }

    /**
     * Get top left latitude.
     *
     * @return  integer
     */
    public function getLeftyLatitude()
    {
        return $this->_leftyLatitude;
    }

    /**
     * Set top left latitude.
     *
     * @param  integer  $_leftyLatitude  top left latitude.
     *
     * @return  self
     */
    public function setLeftyLatitude($leftyLatitude)
    {
        $this->_leftyLatitude = $leftyLatitude;

        return $this;
    }

    /**
     * Get top left longitude.
     *
     * @return  integer
     */
    public function getLeftxLongitude()
    {
        return $this->_leftxLongitude;
    }

    /**
     * Set top left longitude.
     *
     * @param  integer  $_leftxLongitude  top left longitude.
     *
     * @return  self
     */
    public function setLeftxLongitude($leftxLongitude)
    {
        $this->_leftxLongitude = $leftxLongitude;

        return $this;
    }

    /**
     * Get a latitude located in the picture on the bottom right.
     *
     * @return  float
     */
    public function getRightLatitude()
    {
        return $this->_rightLatitude;
    }

    /**
     * Set a latitude located in the picture on the bottom right.
     *
     * @param  float  $_rightLatitude  A latitude located in the picture on the bottom right.
     *
     * @return  self
     */
    public function setRightLatitude($rightLatitude)
    {
        $this->_rightLatitude = $rightLatitude;

        return $this;
    }

    /**
     * Get a longitude located in the picture on the bottom right.
     *
     * @return  float
     */
    public function getRightLongitude()
    {
        return $this->_rightLongitude;
    }

    /**
     * Set a longitude located in the picture on the bottom right.
     *
     * @param  float  $_rightLongitude  A longitude located in the picture on the bottom right.
     *
     * @return  self
     */
    public function setRightLongitude($rightLongitude)
    {
        $this->_rightLongitude = $rightLongitude;

        return $this;
    }

    /**
     * Get bottom right latitude.
     *
     * @return  integer
     */
    public function getRightyLatitude()
    {
        return $this->_rightyLatitude;
    }

    /**
     * Set bottom right latitude.
     *
     * @param  integer  $_rightyLatitude  bottom right latitude.
     *
     * @return  self
     */
    public function setRightyLatitude($rightyLatitude)
    {
        $this->_rightyLatitude = $rightyLatitude;

        return $this;
    }

    /**
     * Get bottom right longitude.
     *
     * @return  integer
     */
    public function getRightxLongitude()
    {
        return $this->_rightxLongitude;
    }

    /**
     * Set bottom right longitude.
     *
     * @param  integer  $_rightxLongitude  bottom right longitude.
     *
     * @return  self
     */
    public function setRightxLongitude($rightxLongitude)
    {
        $this->_rightxLongitude = $rightxLongitude;

        return $this;
    }
}
