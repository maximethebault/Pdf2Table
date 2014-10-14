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
     * The Dimension for the current page
     *
     * @var Dimension
     */
    private $_pageDims;

    /**
     * @param $xmlPage XmlElements\Page a XML representation of this PDF page
     */
    public function __construct($xmlPage) {
        $this->_xmlPage = $xmlPage;
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
        $this->_pageDims = new Dimension($dims);
        $gdImage = imagecreatetruecolor($this->_pageDims->getWidth(), $this->_pageDims->getHeight());

        $this->drawRecursive($gdImage, $this->_xmlPage);

        imagepng($gdImage, $outFile);
        imagedestroy($gdImage);
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
        if(mt_rand(0, 1) == 1) {
            return;
        }
        $dims = new Dimension($dims, $this->_pageDims);
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