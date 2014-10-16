<?php

namespace Maximethebault\Pdf2Table;

/**
 * Class Table
 *
 * Object representation of a table
 * Basically, a grid. If two (or more) cells are spanned, they'll all return an instance of the same object (thanks to PHP references)
 *
 * @package Maximethebault\Pdf2Table
 */
class Table
{
    /**
     * The parent page
     *
     * @var PdfPage
     */
    private $_page;
    /**
     * @var TableRow[]
     */
    private $_rows;

    /**
     * @param $page PdfPage the parent page
     */
    public function __construct($page) {
        $this->_page = $page;
    }

    public function buildTable() {
        $this->_rows = array();
        $nbHorizontalLines = count($this->_page->getHorizontalLines());
        for($i = 0; $i < $nbHorizontalLines; $i++) {
            $this->_rows[$i] = new TableRow($this);
        }
    }
} 