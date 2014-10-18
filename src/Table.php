<?php

namespace Maximethebault\Pdf2Table;

/**
 * Class Table
 *
 * Object representation of a table
 * Basically, a grid. If two (or more) cells are spanned, they'll all return an instance of the same object (thanks to PHP references)
 *
 * @package Maximethebault\Pdf2Table
 */
class Table
{
    /**
     * The parent page
     *
     * @var XmlElements\Page
     */
    private $_page;
    /**
     * The set of horizontal lines, grouped by level
     * level => array of Lines
     *
     * @var array
     */
    private $_horizontalLineGroup;
    /**
     * The set of vertical lines, grouped by level
     * level => array of Lines
     *
     * @var array
     */
    private $_verticalLineGroup;
    /**
     * @var TableCell[][]
     */
    private $_cells;
    /**
     * @var int
     */
    private $_nbCells;

    /**
     * @param $page XmlElements\Page the parent page
     */
    public function __construct($page) {
        $this->_page = $page;
        $this->groupLines($page->getHorizontalLines(), $this->_horizontalLineGroup);
        $this->groupLines($page->getVerticalLines(), $this->_verticalLineGroup);
    }

    /**
     * Get a TableCell identified by its row index and its column index
     * WARNING: if some cells are spanned, act like they're not when you're calling this method!
     *          two spanned cells will hold a reference to the same TableCell object, but there will be two indexes (or more) to access it.
     * Example :
     * if the second and third columns of the first row are spanned, getCell(0,1) == getCell(0,2)
     *
     * @param $row int row's index
     * @param $col int column's index
     *
     * @return TableCell
     */
    public function getCell($row, $col) {
        if(!is_array($this->_cells)) {
            $this->buildTable();
        }
        if($row < 0 || $row >= count($this->_cells)) {
            return null;
        }
        /** @noinspection PhpParamsInspection */
        if($col < 0 || $col >= count($this->_cells[0])) {
            return null;
        }
        return $this->_cells[$row][$col];
    }

    /**
     * @return array
     */
    public function getHorizontalLineGroup() {
        return $this->_horizontalLineGroup;
    }

    /**
     * @return array
     */
    public function getVerticalLineGroup() {
        return $this->_verticalLineGroup;
    }

    /**
     * ** For debug purpose mainly **
     *
     * Draws a page and save it as a PNG image to the specified path
     *
     * @param $outFile string the path to write the image to
     */
    public function drawTable($outFile) {
        if(!is_array($this->_cells)) {
            $this->buildTable();
        }
        $gdImage = imagecreatetruecolor($this->_page->getPageDims()->getWidth(), $this->_page->getPageDims()->getHeight());

        $this->drawRecursive($gdImage);

        imagepng($gdImage, $outFile);
        imagedestroy($gdImage);
    }

    private function buildTable() {
        $this->_cells = array();
        $nbHorizontalLines = count($this->_horizontalLineGroup);
        $nbVerticalLines = count($this->_verticalLineGroup);
        $this->_nbCells = $nbHorizontalLines * $nbVerticalLines;
        $horizontalLevels = array_keys($this->_horizontalLineGroup);
        $verticalLevels = array_keys($this->_verticalLineGroup);
        if($this->_nbCells == 0) {
            return;
        }
        $yStartPoint = $horizontalLevels[0];
        for($i = 1; $i < $nbHorizontalLines; $i++) {
            $yEndPoint = $horizontalLevels[$i];
            $xStartPoint = $verticalLevels[0];
            for($j = 1; $j < $nbVerticalLines; $j++) {
                $xEndPoint = $verticalLevels[$j];
                $cellBorders = new Border($xStartPoint . ',' . $yStartPoint . ',' . $xEndPoint . ',' . $yEndPoint);
                $this->_cells[$i - 1][$j - 1] = new TableCell($this, $cellBorders);
                $xStartPoint = $xEndPoint;
            }
            $yStartPoint = $yEndPoint;
        }
        $this->runMergeAlgorithm();
    }

    /**
     * Merges cells that are spanned
     */
    private function runMergeAlgorithm() {
        $rowCount = count($this->_cells);
        if(!$rowCount) {
            return;
        }
        /** @noinspection PhpParamsInspection */
        $colCount = count($this->_cells[0]);
        do {
            $nbCellsBeginningIteration = $this->_nbCells;
            if($colCount > 1) {
                for($i = 0; $i < $rowCount; $i++) {
                    /** @var $lastCell TableCell */
                    $lastCell = $this->_cells[$i][0];
                    for($j = 1; $j < $colCount; $j++) {
                        if($this->_cells[$i][$j] == $lastCell) {
                            continue;
                        }
                        if($lastCell->glue($this->_cells[$i][$j])) {
                            $this->_cells[$i][$j] = & $this->_cells[$i][$j - 1];
                            $this->_nbCells--;
                        }
                        $lastCell = $this->_cells[$i][$j];
                    }
                }
            }
            if($rowCount > 1) {
                for($j = 0; $j < $colCount; $j++) {
                    /** @var $lastCell TableCell */
                    $lastCell = $this->_cells[0][$j];
                    for($i = 1; $i < $rowCount; $i++) {
                        if($this->_cells[$i][$j] == $lastCell) {
                            continue;
                        }
                        if($lastCell->glue($this->_cells[$i][$j])) {
                            $this->_cells[$i][$j] = & $this->_cells[$i - 1][$j];
                            $this->_nbCells--;
                        }
                        $lastCell = $this->_cells[$i][$j];
                    }
                }
            }
        } while($this->_nbCells != $nbCellsBeginningIteration);
    }

    /**
     * Builds, from an array of ordered lines, groups of lines.
     * All of the Lines that are on the same level will get in the same group. The group can contain from one to several lines.
     *
     * @param $lines Line[] array of lines
     * @param $set   array the resulting groups of lines
     */
    private function groupLines($lines, &$set) {
        $currentKey = $lines[0]->getLevel();
        $set[(string) $lines[0]->getLevel()] = array($lines[0]);
        for($i = 1; $i < count($lines); $i++) {
            if(abs($lines[$i]->getLevel() - $currentKey) < 2) {
                $set[(string) $currentKey][] = $lines[$i];
            }
            else {
                $currentKey = $lines[$i]->getLevel();
                $set[(string) $currentKey] = array($lines[$i]);
            }
        }
    }

    /**
     * Draws the table
     *
     * @param $gdImage    resource the GD Image on which we're drawing
     */
    private function drawRecursive($gdImage) {
        for($i = 0; $i < count($this->_cells); $i++) {
            /** @noinspection PhpParamsInspection */
            for($j = 0; $j < count($this->_cells[0]); $j++) {
                $this->drawCell($gdImage, $this->_cells[$i][$j]);
            }
        }
    }

    /**
     * ** For debug purpose mainly **
     *
     * Draws an element
     *
     * @param $gdImage       resource the GD Image on which we're drawing
     * @param $cell          TableCell the element we're drawing
     *
     * @throws Exception\MissingDimensionException
     */
    private function drawCell($gdImage, $cell) {
        $dims = $cell->getBorder();
        $white = imagecolorallocate($gdImage, 255, 255, 255);
        imagerectangle($gdImage, $dims->getXStart(), $dims->getYStart(), $dims->getXEnd(), $dims->getYEnd(), $white);
    }
} 