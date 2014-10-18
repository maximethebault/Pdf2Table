<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\Pdf2Table\Border;
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
     * @return string this element's tag name
     */
    public function getName() {
        return 'textline';
    }

    /**
     * @param $relativeBorder Border
     */
    public function computeText($relativeBorder) {
        $tempText = '';
        /** @var $tempBorder Border */
        $tempBorder = null;
        foreach($this->text as $text) {
            $content = trim($text->data());
            if($text->attrs('bbox') == null) {
                continue;
            }
            if($content !== null && $content !== '') {
                $this->_textContent .= $tempText . $content;
                if($tempBorder == null) {
                    $tempBorder = new Border($text->attrs('bbox'), $relativeBorder);
                }
                else {
                    $tempBorder->merge(new Border($text->attrs('bbox'), $relativeBorder));
                }
                $this->_textBorder = $tempBorder;
                $tempText = '';
            }
            else {
                if($tempBorder !== null) {
                    $tempBorder->merge(new Border($text->attrs('bbox'), $relativeBorder));
                    $tempText = ' ';
                }
            }
        }
    }

    /**
     * @param $relativeBorder Border
     *
     * @return string
     */
    public function getText($relativeBorder) {
        if(!$this->_textContent) {
            $this->computeText($relativeBorder);
        }
        return $this->_textContent;
    }

    /**
     * @param $relativeBorder Border
     *
     * @return Border
     */
    public function getTextBorder($relativeBorder) {
        if(!$this->_textContent) {
            $this->computeText($relativeBorder);
        }
        return $this->_textBorder;
    }
}