<?php

namespace Maximethebault\Pdf2Table\XmlElements;

use Maximethebault\XmlParser\XmlRootElement;

/**
 * @property Page[] page
 */
class Pages extends XmlRootElement
{
    public $children = array('page' => array('multi' => true, 'class' => 'Maximethebault\Pdf2Table\XmlElements\Page'));

    /**
     * @return string this element's tag name
     */
    public function getName() {
        return 'pages';
    }
}