<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

/**
 * @property Textline[] textline
 */
class Textbox extends XmlElement
{
    public $children = array('textline' => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Textline'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'textbox';
    }
}