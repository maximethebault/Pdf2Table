<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlElement;

/**
 * @property XmlElement[] textbox
 * @property XmlElement[] figure
 * @property XmlElement[] rect
 * @property XmlElement   layout
 */
class Page extends XmlElement
{
    public $children = array('textbox' => array('multi' => true, 'cache_attr' => 'id', 'class' => 'Maximethebault\Pdf2Table\XmlElements\Textbox'),
                             'figure'  => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Figure'),
                             'rect'    => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Rect'),
                             'layout'  => array('class' => 'Maximethebault\Pdf2Table\XmlElements\Layout'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'page';
    }
}