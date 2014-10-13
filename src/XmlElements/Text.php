<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

class Text extends XmlElement
{
    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'text';
    }
}