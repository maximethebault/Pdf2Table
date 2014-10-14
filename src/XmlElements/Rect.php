<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

class Rect extends XmlElement
{
    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'rect';
    }
}