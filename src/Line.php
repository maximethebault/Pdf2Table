<?php

namespace Maximethebault\Pdf2Table;

abstract class Line
{
    /**
     * Borders of the line
     *
     * @var Border
     */
    protected $_border;

    /**
     * Constructs a line from a border
     *
     * @param $border Border the borders of the line
     */
    public function __construct($border) {
        $this->_border = $border;
    }

    /**
     * Glue two lines together, if they're close enough
     *
     * @param $line Line the line we want to merge with
     *
     * @return bool true if the lines were glued together
     */
    public function glue($line) {
        if(abs($this->getLevel() - $line->getLevel()) < 2 && $this->distance($line) < 2) {
            $this->merge($line);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isHorizontal() {
        return $this->_border->isHorizontal();
    }

    /**
     * @return bool
     */
    public function isVertical() {
        return $this->_border->isVertical();
    }

    /**
     * Checks if current line is included in given line
     *
     * @param $biggerLine Line the bigger line of the two to perform the inclusion check
     *
     * @return bool
     */
    public function isIncluded($biggerLine) {
        if($this->isHorizontal() != $biggerLine->isHorizontal()) {
            return false;
        }
        if(abs($this->getLevel() - $biggerLine->getLevel()) > 1) {
            return false;
        }
        // 2 as a security margin
        if($this->getStartPoint() < $biggerLine->getStartPoint() - 2) {
            return false;
        }
        if($this->getEndPoint() > $biggerLine->getEndPoint() + 2) {
            return false;
        }
        return true;
    }

    /**
     * @param $line Line
     *
     * @return bool
     */
    public function equals($line) {
        return ($this->_border->equals($line->_border));
    }

    /**
     * Gets the "level" of a line
     * A level is defined as the constant parameter of the line
     * For horizontal lines, it's the y value
     * For vertical lines, it's the x value
     *
     * @return float
     */
    abstract public function getLevel();

    /**
     * Gets the starting point for the line
     *
     * E.g.: For horizontal lines, it's xStart
     *
     * @return float
     */
    abstract public function getStartPoint();

    /**
     * Gets the ending point for the line
     *
     * E.g.: For horizontal lines, it's xEnd
     *
     * @return float
     */
    abstract public function getEndPoint();

    /**
     * Get the distance between two lines
     *
     * @param $line Line the line we want to measure the distance with
     *
     * @return float the distance between the lines, 0 if one starts after the other ends
     */
    abstract protected function distance($line);

    /**
     * Merges two lines
     *
     * @param $line Line
     */
    abstract protected function merge($line);
} 