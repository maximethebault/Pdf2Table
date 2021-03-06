<?php

namespace Maximethebault\Pdf2Table;

use Maximethebault\Pdf2Table\XmlElements\Textline;

/**
 * Class TableCell
 *
 * Reprensts a cell from a table
 * A cell, even spanned, is always rectangular
 *
 * @package Maximethebault\Pdf2Table
 */
class TableCell
{
    /**
     * @var Table
     */
    private $_parentTable;
    /**
     * @var Border
     */
    private $_border;
    /**
     * @var Textline[]
     */
    private $_textline;

    /**
     * Constructs a table cell from its parent table and its borders
     *
     * @param $parentTable Table
     * @param $borders     Border
     */
    public function __construct($parentTable, $borders) {
        $this->_parentTable = $parentTable;
        $this->_border = $borders;
        $this->_textline = array();
    }

    /**
     * Glue two cells together, if no line was detected in-between
     *
     * @param $cell TableCell the cell to glue with
     *
     * @return bool true if the cells were glued together
     */
    public function glue($cell) {
        if(!$this->hasLineInBetween($cell)) {
            $this->merge($cell);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return Border
     */
    public function getPageDims() {
        return $this->_parentTable->getPageDims();
    }

    /**
     * @return Border
     */
    public function getBorder() {
        return $this->_border;
    }

    /**
     * @param $textline Textline
     */
    public function addTextline($textline) {
        $textline->setParentCell($this);
        $this->_textline[] = $textline;
    }

    /**
     * @return Textline[]
     */
    public function getTextline() {
        return $this->_textline;
    }

    /**
     * Indicates if two cells have a line in-between
     *
     * @param $cell TableCell the cell to check with
     *
     * @return bool whether the two cells have a line in-between
     */
    private function hasLineInBetween($cell) {
        // firstly, get the line that should seperate these two cells
        $borderIntersection = $this->_border->getIntersectingLine($cell->_border);
        // then, check if it really exists !
        if(!$borderIntersection) {
            // can happen if a cell is larger than the other
            return true;
        }
        if($borderIntersection->isHorizontal()) {
            foreach($this->_parentTable->getHorizontalLineGroup()[(string) $borderIntersection->getLevel()] as $horizontalLine) {
                if($borderIntersection->isIncluded($horizontalLine)) {
                    return true;
                }
            }
        }
        if($borderIntersection->isVertical()) {
            foreach($this->_parentTable->getVerticalLineGroup()[(string) $borderIntersection->getLevel()] as $verticalLine) {
                if($borderIntersection->isIncluded($verticalLine)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Merges two cells together
     *
     * @param $cell TableCell the cell to merge with
     */
    private function merge($cell) {
        $this->_border->merge($cell->_border);
    }
} 