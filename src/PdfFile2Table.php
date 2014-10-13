<?php

namespace Maximethebault\Pdf2Table;

//require __DIR__ . '../vendor/autoload.php';

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

    public function __construct($filePath) {
        $this->_filePath = $filePath;
    }

    public function parse() {
        if(!file_exists($this->_filePath)) {
            throw new Exception\FileNotFoundException('PDF file on input of PdfFile2Table not found : ' . $this->_filePath . '.');
        }
        $xmlUniqueName = uniqid() . '.xml';
        exec('pdf2txt.py -o ' . $xmlUniqueName . ' ' . $this->_filePath);
        $xmlConfig = new XmlParserConfig();
        $xmlConfig->addXmlElementFolder('XmlElements/', 'Maximethebault\Pdf2Table\XmlElements');
        $xmlFileParser = new XmlFileParser($this->_filePath, $xmlConfig, new Pages());
        $xmlRes = $xmlFileParser->parseFile();
        unlink($xmlUniqueName);
        return $xmlRes;
    }
}