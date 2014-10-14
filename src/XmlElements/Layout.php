<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

/**
 * @property XmlElement   textgroup
 */
class Layout extends XmlElement
{
    public $children = array('textgroup' => array('class' => 'Maximethebault\Pdf2Table\XmlElements\Textgroup'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'layout';
    }
}