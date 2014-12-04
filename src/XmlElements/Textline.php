<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\Pdf2Table\Border;
use Maximethebault\Pdf2Table\TableCell;
use Maximethebault\XmlParser\XmlElement;

/**
 * @property Text[] text
 */
class Textline extends XmlElement
{
    public $children = array('text' => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Text'));
    /**
     * The text, without extra spaces at beginng and end of the words
     *
     * @var string
     */
    private $_textContent;
    /**
     * @var  \Maximethebault\Pdf2Table\Border the border containing this line of text
     */
    private $_textBorder;
    /**
     * @var float the size of the biggest character(s) in the text
     */
    private $_maxSize;
    /**
     * @var TableCell the cell the textline is attached to
     */
    private $_parentCell;

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'textline';
    }

    /**
     * @return string
     */
    public function getText() {
        if(!$this->_textContent) {
            $this->computeText();
        }
        return $this->_textContent;
    }

    /**
     * @return Border
     */
    public function getTextBorder() {
        if(!$this->_textContent) {
            $this->computeText();
        }
        return $this->_textBorder;
    }

    /**
     * @return float
     */
    public function getMaxCharSize() {
        if(!$this->_textContent) {
            // that call is needed to compute the maxSize
            $this->computeText();
        }
        return $this->_maxSize;
    }

    /**
     * @return float
     */
    public function getParentCellSize() {
        return $this->_parentCell->getBorder()->getWidth();
    }

    public function wouldFit($str) {
        if(!$this->_parentCell) {
            return true;
        }
        $availableSpace = $this->_parentCell->getBorder()->getWidth() - $this->getTextBorder()->getWidth();
        // strlen + 1 because when we add a word to a line, we also need a space
        $neededSpace = $this->_maxSize * (strlen($str) + 1);
        if($availableSpace - $neededSpace > $this->_maxSize * 2) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param TableCell $parentCell
     */
    public function setParentCell($parentCell) {
        $this->_parentCell = $parentCell;
    }

    /**
     * Strip all spaces at the beginning and at the end of the textline, and updates the textBorder/textContent accordingly
     */
    private function computeText() {
        $tempText = '';
        $this->_maxSize = 0;
        /** @var $tempBorder Border */
        $tempBorder = null;
        foreach($this->text as $text) {
            $content = trim($text->data());
            if($text->attrs('bbox') == null) {
                continue;
            }
            if($content !== null && $content !== '') {
                $this->_textContent .= $tempText . $content;
                if($text->attrs('size')) {
                    $this->_maxSize = max($this->_maxSize, $text->attrs('size'));
                }
                if($tempBorder == null) {
                    $tempBorder = new Border($text->attrs('bbox'), true);
                }
                else {
                    $tempBorder->merge(new Border($text->attrs('bbox'), true));
                }
                $this->_textBorder = $tempBorder;
                $tempText = '';
            }
            else {
                if($tempBorder !== null) {
                    $tempBorder->merge(new Border($text->attrs('bbox'), true));
                    $tempText = ' ';
                }
            }
        }
    }
}