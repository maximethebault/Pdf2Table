<?php

namespace Maximethebault\Pdf2Table;

/**
 * Class TableRow
 *
 * Object representation of a table row
 *
 * @package Maximethebault\Pdf2Table
 */
class TableRow
{
    /**
     * The parent page
     *
     * @var PdfPage
     */
    private $_page;
    /**
     * @var TableCell[]
     */
    private $_cells;

    /**
     * @param $page PdfPage the parent page
     */
    public function __construct($page) {
        $this->_page = $page;
    }

    public function buildRow() {
        $this->_rows = array();
        $nbHorizontalLines = count($this->_page->getHorizontalLines());
        for($i = 0; $i < $nbHorizontalLines; $i++) {
            $this->_rows[$i] = new TableRow($this);
        }
    }
} 