<?php

namespace Maximethebault\Pdf2Table;

/**
 * Class PdfFile2Table
 *
 * Parses a PDF file into a table
 */
class PdfFile2Table
{
    /**
     * Path to the PDF file
     *
     * @var string
     */
    private $_filePath;

    public function __construct($filePath) {
        $this->_filePath = $filePath;
    }

    public function parse() {
        if(!file_exists($this->_filePath)) {
            throw new Exception\FileNotFoundException('PDF file on input of PdfFile2Table not found : ' . $this->_filePath . '.');
        }
    }
}