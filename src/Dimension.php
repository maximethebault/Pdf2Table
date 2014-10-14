<?php

namespace Maximethebault\Pdf2Table;

use Maximethebault\Pdf2Table\Exception\MissingDimensionException;

class Dimension
{
    /**
     * Coordinates for the dimensions
     *
     * @var float
     */
    private $_xStart, $_yStart, $_xEnd, $_yEnd;

    /**
     * @param $str           string the string we're building the Dimension object from
     * @param $pageDimension Dimension necessary to convert the PDF coordinates (which lives in a frame where 0,0 is the bottom left corner) into a frame adpadted to PHP's GD library (0,0 is top left corner)
     *
     * @throws Exception\MissingDimensionException
     */
    public function __construct($str, $pageDimension = null) {
        $dims = explode(',', $str);
        if(count($dims) != 4) {
            throw new MissingDimensionException;
        }
        $this->_xStart = (float) $dims[0];
        $this->_yStart = ($pageDimension != null) ? ($pageDimension->getHeight() - (float) $dims[1]) : (float) $dims[1];
        $this->_xEnd = (float) $dims[2];
        $this->_yEnd = ($pageDimension != null) ? ($pageDimension->getHeight() - (float) $dims[3]) : (float) $dims[3];
    }

    /**
     * Width
     *
     * @return float
     */
    public function getWidth() {
        return $this->_xEnd - $this->_xStart;
    }

    /**
     * Height
     *
     * @return float
     */
    public function getHeight() {
        return $this->_yEnd - $this->_yStart;
    }

    /**
     * Coordinate start on X-axis
     *
     * @return float
     */
    public function getXStart() {
        return $this->_xStart;
    }

    /**
     * Coordinate start on Y-axis
     *
     * @return float
     */
    public function getYStart() {
        return $this->_yStart;
    }

    /**
     * Coordinate end on X-axis
     *
     * @return float
     */
    public function getXEnd() {
        return $this->_xEnd;
    }

    /**
     * Coordinate end on Y-axis
     *
     * @return float
     */
    public function getYEnd() {
        return $this->_yEnd;
    }

    /**
     * Returns true if the current Dimension is representative of an horizontal object
     *
     * @return bool
     */
    public function isHorizontal() {
        return ($this->getHeight() < 2);
    }

    /**
     * Returns true if the current Dimension is representative of a vertical object
     *
     * @return bool
     */
    public function isVertical() {
        return ($this->getWidth() < 2);
    }
}