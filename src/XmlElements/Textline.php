<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

/**
 * @property XmlElement[] text
 */
class Textline extends XmlElement
{
    public $children = array('text' => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Text'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'textline';
    }
}