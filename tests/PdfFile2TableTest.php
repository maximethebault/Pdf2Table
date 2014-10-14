<?php

namespace Maximethebault\Pdf2Table\Tests;

use Maximethebault\Pdf2Table\PdfFile2Table;

class PdfFile2TableTest extends \PHPUnit_Framework_TestCase
{
    public function __construct() {
    }

    public function testPdfExtraction() {
        $pdfFile2Table = new PdfFile2Table(__DIR__ . "/Res/menu40.pdf");
        $pdfFile2Table->parse()->getPages()[0]->drawPage('menu40.png');
    }
}