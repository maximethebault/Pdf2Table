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
        $this->assertEquals("40", $pdfFile2Table->parse()->getPages()[0]->getRows()[0]->getCols()[0]->getTexts()[0]);
        $this->assertEquals("Déjeuner", $pdfFile2Table->parse()->getPages()[0]->getRows()[0]->getCols()[1]->getTexts()[0]);
        $this->assertEquals("Dîner", $pdfFile2Table->parse()->getPages()[0]->getRows()[0]->getCols()[2]->getTexts()[0]);
        $this->assertEquals("Lundi 29", $pdfFile2Table->parse()->getPages()[0]->getRows()[1]->getCols()[0]->getTexts()[0]);
        $this->assertEquals("Feuilleté dubarry", $pdfFile2Table->parse()->getPages()[0]->getRows()[1]->getCols()[1]->getTexts()[0]);
        $this->assertEquals("Céléri rémoulade", $pdfFile2Table->parse()->getPages()[0]->getRows()[1]->getCols()[1]->getTexts()[1]);
        $this->assertEquals("Betteraves-maïs", $pdfFile2Table->parse()->getPages()[0]->getRows()[1]->getCols()[1]->getTexts()[2]);
        $this->assertEquals("Curry d’agneau", $pdfFile2Table->parse()->getPages()[0]->getRows()[1]->getCols()[2]->getTexts()[0]);
    }
}