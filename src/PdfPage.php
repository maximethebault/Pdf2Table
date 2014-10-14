<?php

namespace Maximethebault\Pdf2Table;

use Maximethebault\Pdf2Table\Exception\MissingDimensionException;

class PdfPage
{
    /**
     * XML representation of this PDF page
     *
     * @var XmlElements\Page
     */
    private $_xmlPage;
    /**
     * The Border for the current page
     *
     * @var Border
     */
    private $_pageDims;
    /**
     * Array of horizontal/vertical lines
     *
     * @var Line[]
     */
    private $_horizontalLines, $_verticalLines;

    /**
     * @param $xmlPage XmlElements\Page a XML representation of this PDF page
     */
    public function __construct($xmlPage) {
        $this->_xmlPage = $xmlPage;
        $this->_horizontalLines[] = array();
        $this->_verticalLines[] = array();
    }

    /**
     * ** For debug purpose mainly **
     *
     * Draws a page and save it as a PNG image to the specified path
     *
     * @param $outFile string the path to write the image to
     *
     * @throws Exception\MissingDimensionException
     */
    public function drawPage($outFile) {
        if(($dims = $this->_xmlPage->attrs('bbox')) == null) {
            throw new MissingDimensionException();
        }
        $this->_pageDims = new Border($dims);
        $gdImage = imagecreatetruecolor($this->_pageDims->getWidth(), $this->_pageDims->getHeight());

        $this->drawRecursive($gdImage, $this->_xmlPage);

        imagepng($gdImage, $outFile);
        imagedestroy($gdImage);
    }

    private function getLines() {
        foreach($this->_xmlPage->rect as $line) {
            $border = $line->attrs('bbox');
            if(!$border) {
                continue;
            }
            $line = new Line($border);
            if($line->isHorizontal()) {
                $this->_horizontalLines[] = $line;
            }
            else if($line->isVertical()) {
                $this->_verticalLines[] = $line;
            }
        }
    }

    private function extendLines($lineSet) {

        do {
            $lineCount = count($lineSet);
        } while($lineCount != count($lineSet));
    }

    /**
     * ** For debug purpose mainly **
     *
     * Draws an element
     *
     * @param $gdImage       resource the GD Image on which we're drawing
     * @param $xmlElement    \Maximethebault\XmlParser\XmlElement the XML element we're drawing
     *
     * @throws Exception\MissingDimensionException
     */
    private function drawElement($gdImage, $xmlElement) {
        if(($dims = $xmlElement->attrs('bbox')) == null || $xmlElement->getName() != 'rect') {
            return;
        }
        $dims = new Border($dims, $this->_pageDims);
        $white = imagecolorallocate($gdImage, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        imagerectangle($gdImage, $dims->getXStart(), $dims->getYStart(), $dims->getXEnd(), $dims->getYEnd(), $white);
    }

    /**
     * Draws the children of a XML element
     *
     * @param $gdImage    resource the GD Image on which we're drawing
     * @param $xmlElement \Maximethebault\XmlParser\XmlElement the XML element whose children will be drawn
     */
    private function drawRecursive($gdImage, $xmlElement) {
        foreach($xmlElement->getChildren() as $elements) {
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