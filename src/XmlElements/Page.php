<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\Pdf2Table\Border;
use Maximethebault\Pdf2Table\Exception\MissingDimensionException;
use Maximethebault\Pdf2Table\HorizontalLine;
use Maximethebault\Pdf2Table\Line;
use Maximethebault\Pdf2Table\Table;
use Maximethebault\Pdf2Table\VerticalLine;
use Maximethebault\XmlParser\XmlElement;

/**
 * @property Textbox[] textbox
 * @property Figure[]  figure
 * @property Rect[]    rect
 * @property Layout    layout
 */
class Page extends XmlElement
{
    public $children = array('textbox' => array('multi' => true, 'cache_attr' => 'id', 'class' => 'Maximethebault\Pdf2Table\XmlElements\Textbox'),
                             'figure'  => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Figure'),
                             'rect'    => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Rect'),
                             'layout'  => array('class' => 'Maximethebault\Pdf2Table\XmlElements\Layout'));

    /**
     * Array of horizontal/vertical lines
     *
     * @var Line[]
     */
    private $_horizontalLines, $_verticalLines;
    /**
     * Object holding the table & its cells
     *
     * @var Table
     */
    private $_table;

    public function __construct() {
        $this->_horizontalLines = array();
        $this->_verticalLines = array();
        call_user_func_array(array('parent', '__construct'), func_get_args());
    }

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'page';
    }

    public function getTable() {
        if(!$this->_table) {
            $this->buildTable();
        }
        return $this->_table;
    }

    /**
     * ** For debug purpose mainly **
     *
     * Draws a page and save it as a PNG image to the specified path
     *
     * @param $outFile string the path to write the image to
     *
     * @throws MissingDimensionException
     */
    public function drawPage($outFile) {
        $gdImage = imagecreatetruecolor($this->getPageDims()->getWidth(), $this->getPageDims()->getHeight());

        $this->drawRecursive($gdImage);

        imagepng($gdImage, $outFile);
        imagedestroy($gdImage);
    }

    /**
     * @return Line[]
     */
    public function getHorizontalLines() {
        return $this->_horizontalLines;
    }

    /**
     * @return Line[]
     */
    public function getVerticalLines() {
        return $this->_verticalLines;
    }

    /**
     * @return Border
     *
     * @throws \Maximethebault\Pdf2Table\Exception\MissingDimensionException
     */
    public function getPageDims() {
        if(($dims = $this->attrs('bbox')) == null) {
            throw new MissingDimensionException();
        }
        return new Border($dims);
    }

    private function buildTable() {
        Border::setPageDimension($this->getPageDims());
        $this->computeLines();
        $this->expandLines($this->_horizontalLines);
        $this->expandLines($this->_verticalLines);
        $this->sortLines($this->_horizontalLines);
        $this->sortLines($this->_verticalLines);
        $this->_table = new Table($this);
    }

    private function computeLines() {
        foreach($this->rect as $line) {
            $border = $line->attrs('bbox');
            if(!$border) {
                continue;
            }
            $border = new Border($border, true);
            if($border->isHorizontal()) {
                $this->_horizontalLines[] = new HorizontalLine($border);
            }
            else if($border->isVertical()) {
                $this->_verticalLines[] = new VerticalLine($border);
            }
        }
    }

    /**
     * Expands a set of lines, i.e. it merges lines which should be considered as one
     *
     * @param $lineSet Line[] an array of lines
     */
    private function expandLines(&$lineSet) {
        // this "algorithm" is absolutely unoptimized
        do {
            $lineCount = count($lineSet);
            for($i = 0; $i < $lineCount; $i++) {
                $line1 = $lineSet[$i];
                for($j = $i + 1; $j < $lineCount; $j++) {
                    $line2 = $lineSet[$j];
                    if(!$line1 || !$line2) {
                        continue;
                    }
                    if($line1->glue($line2)) {
                        unset($lineSet[$j]);
                        break 2;
                    }
                }
            }
            // when we unset an element off an array, key still exists and returns null.
            // we need to clean up this mess
            $lineSet = array_values($lineSet);
        } while($lineCount != count($lineSet));
    }

    /**
     * Sorts a set of lines
     *
     * @param $lineSet ..\Line[] an array of lines
     */
    private function sortLines(&$lineSet) {
        usort($lineSet, function ($line1, $line2) {
            /** @noinspection PhpUndefinedMethodInspection */
            return $line1->getLevel() - $line2->getLevel();
        });
    }

    /**
     * ** For debug purpose mainly **
     *
     * Draws an element
     *
     * @param $gdImage       resource the GD Image on which we're drawing
     * @param $xmlElement    \Maximethebault\XmlParser\XmlElement the XML element we're drawing
     *
     * @throws ..\Exception\MissingDimensionException
     */
    private function drawElement($gdImage, $xmlElement) {
        if(($dims = $xmlElement->attrs('bbox')) == null) {
            return;
        }
        $dims = new Border($dims, true);
        $white = imagecolorallocate($gdImage, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagerectangle($gdImage, $dims->getXStart(), $dims->getYStart(), $dims->getXEnd(), $dims->getYEnd(), $white);
    }

    /**
     * Draws the children of a XML element
     *
     * @param $gdImage    resource the GD Image on which we're drawing
     */
    private function drawRecursive($gdImage) {
        foreach($this->getChildren() as $elements) {
            if(is_array($elements)) {
                foreach($elements as $element) {
                    $this->drawElement($gdImage, $element);
                }
            }
            else {
                $this->drawElement($gdImage, $elements);
            }
        }
    }
}