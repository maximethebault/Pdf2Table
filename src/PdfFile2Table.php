<?php

namespace Maximethebault\Pdf2Table;

use Maximethebault\Pdf2Table\XmlElements\Pages;
use Maximethebault\XmlParser\XmlFileParser;
use Maximethebault\XmlParser\XmlParserConfig;

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
    /**
     * The root element of the XML representation of the PDF
     *
     * @var XmlElements\Pages
     */
    private $_pages;

    public function __construct($filePath) {
        $this->_filePath = $filePath;
    }

    /**
     * Starts the parsing of the PDF
     *
     * @param $tempPath string the path used to store the temporary file (don't forget the trailing slash!)
     *
     * @return $this
     *
     * @throws Exception\FileNotFoundException
     */
    public function parse($tempPath = null) {
        if(!file_exists($this->_filePath)) {
            throw new Exception\FileNotFoundException('PDF file on input of PdfFile2Table not found : ' . $this->_filePath . '.');
        }
        if(!$tempPath) {
            $tempPath = __DIR__ . '/../';
        }
        $xmlUniqueName = $tempPath . uniqid() . '.xml';
        exec('pdf2txt.py -o ' . escapeshellarg(realpath($xmlUniqueName)) . ' ' . escapeshellarg(realpath($this->_filePath)));
        $xmlConfig = new XmlParserConfig();
        $xmlConfig->addXmlElementFolder('XmlElements/', 'Maximethebault\Pdf2Table\XmlElements');
        $xmlFileParser = new XmlFileParser($xmlUniqueName, $xmlConfig, new Pages());
        $xmlRes = $xmlFileParser->parseFile();
        $this->_pages = $xmlRes;
        unlink($xmlUniqueName);
        return $this;
    }

    /**
     * Gets the array of PDF pages
     *
     * @return XmlElements\Page[]
     */
    public function getPages() {
        return $this->_pages->page;
    }
}