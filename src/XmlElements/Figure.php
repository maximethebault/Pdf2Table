<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

class Figure extends XmlElement
{
    public $children = array('image' => array('class' => 'Maximethebault\Pdf2Table\XmlElements\Image'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'figure';
    }
}