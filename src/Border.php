<?php

namespace Maximethebault\Pdf2Table;

use Maximethebault\Pdf2Table\Exception\MissingDimensionException;

class Border
{
    /**
     * Coordinates for the dimensions
     *
     * @var float
     */
    protected $_xStart, $_yStart, $_xEnd, $_yEnd;

    /**
     * @param $str           string the string we're building the Border object from
     * @param $pageDimension Border necessary to convert the PDF coordinates (which lives in a frame where 0,0 is the bottom left corner) into a frame adpadted to PHP's GD library (0,0 is top left corner)
     *
     * @throws Exception\MissingDimensionException
     */
    public function __construct($str, $pageDimension = null) {
        $dims = explode(',', $str);
        if(count($dims) != 4) {
            throw new MissingDimensionException;
        }
        $this->_xStart = (float) $dims[0];
        // when we're doing a substraction, pay attention to the order:
        // yStart should be inferior to yEnd
        $this->_yStart = ($pageDimension != null) ? ($pageDimension->getHeight() - (float) $dims[3]) : (float) $dims[1];
        $this->_xEnd = (float) $dims[2];
        $this->_yEnd = ($pageDimension != null) ? ($pageDimension->getHeight() - (float) $dims[1]) : (float) $dims[3];
    }

    /**
     * When two borders are side by side, they share one of their borders
     * Let's find it!
     *
     * @param $border Border the Border to compute the intersecting line with
     *
     * @return Line the intersecting line if one was found, null otherwise
     */
    public function getIntersectingLine($border) {
        if($this->getTopLine()->equals($border->getBottomLine())) {
            return $this->getTopLine();
        }
        elseif($this->getBottomLine()->equals($border->getTopLine())) {
            return $this->getBottomLine();
        }
        elseif($this->getLeftLine()->equals($border->getRightLine())) {
            return $this->getLeftLine();
        }
        elseif($this->getRightLine()->equals($border->getLeftLine())) {
            return $this->getRightLine();
        }
        else {
            return null;
        }
    }

    /**
     * Merges two borders together
     *
     * @param $border Border the Border to be merged into the current one
     */
    public function merge($border) {
        $this->setXStart(min($this->getXStart(), $border->getXStart()));
        $this->setYStart(min($this->getYStart(), $border->getYStart()));
        $this->setXEnd(max($this->getXEnd(), $border->getXEnd()));
        $this->setYEnd(max($this->getYEnd(), $border->getYEnd()));
    }

    /**
     * @return HorizontalLine
     */
    public function getTopLine() {
        return new HorizontalLine(new Border($this->getXStart() . ',' . $this->getYStart() . ',' . $this->getXEnd() . ',' . $this->getYStart()));
    }

    /**
     * @return HorizontalLine
     */
    public function getBottomLine() {
        return new HorizontalLine(new Border($this->getXStart() . ',' . $this->getYEnd() . ',' . $this->getXEnd() . ',' . $this->getYEnd()));
    }

    /**
     * @return VerticalLine
     */
    public function getLeftLine() {
        return new VerticalLine(new Border($this->getXStart() . ',' . $this->getYStart() . ',' . $this->getXStart() . ',' . $this->getYEnd()));
    }

    /**
     * @return VerticalLine
     */
    public function getRightLine() {
        return new VerticalLine(new Border($this->getXEnd() . ',' . $this->getYStart() . ',' . $this->getXEnd() . ',' . $this->getYEnd()));
    }

    /**
     * @param $border Border
     *
     * @return bool
     */
    public function equals($border) {
        return abs($this->getXStart() - $border->getXStart()) < 1 && abs($this->getXEnd() - $border->getXEnd()) < 1 && abs($this->getYStart() - $border->getYStart()) < 1 && abs($this->getYEnd() - $border->getYEnd()) < 1;
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
     * Returns true if the current Border is representative of an horizontal object
     *
     * @return bool
     */
    public function isHorizontal() {
        return ($this->getHeight() < 2);
    }

    /**
     * Returns true if the current Border is representative of a vertical object
     *
     * @return bool
     */
    public function isVertical() {
        return ($this->getWidth() < 2);
    }

    /**
     * @param float $xStart
     */
    public function setXStart($xStart) {
        $this->_xStart = $xStart;
    }

    /**
     * @param float $yStart
     */
    public function setYStart($yStart) {
        $this->_yStart = $yStart;
    }

    /**
     * @param float $xEnd
     */
    public function setXEnd($xEnd) {
        $this->_xEnd = $xEnd;
    }

    /**
     * @param float $yEnd
     */
    public function setYEnd($yEnd) {
        $this->_yEnd = $yEnd;
    }

    /**
     * Determines how much of $textBorder is covered by $this (0 to 100%)
     *
     * @param $border Border the other Border
     *
     * @return int percent, between 0 and 100
     */
    public function coveringPercent($border) {
        $common = $this->getCommonBorder($border);
        if(!$common) {
            return 0;
        }
        $commonSurface = $common->getWidth() * $common->getHeight();
        $totalSurface = $border->getWidth() * $border->getHeight();
        return (int) ($commonSurface / $totalSurface * 100);
    }

    /**
     *
     *
     * @param $border Border
     *
     * @return Border the common border
     */
    private function getCommonBorder($border) {
        $xStart = max($this->getXStart(), $border->getXStart());
        $yStart = max($this->getYStart(), $border->getYStart());
        $xEnd = min($this->getXEnd(), $border->getXEnd());
        $yEnd = min($this->getYEnd(), $border->getYEnd());
        if($xEnd < $xStart || $yEnd < $yStart) {
            return null;
        }
        return new Border($xStart . ',' . $yStart . ',' . $xEnd . ',' . $yEnd);
    }
}