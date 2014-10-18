<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

/**
 * @property Textgroup[] textgroup
 * @property Textbox[]   textbox
 */
class Textgroup extends XmlElement
{
    public $children = array('textgroup' => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Textgroup'),
                             'textbox'   => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Textbox'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'textgroup';
    }
}