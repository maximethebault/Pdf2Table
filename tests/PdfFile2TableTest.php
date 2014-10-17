<?php

namespace Maximethebault\Pdf2Table\Tests;

use Maximethebault\Pdf2Table\PdfFile2Table;

class PdfFile2TableTest extends \PHPUnit_Framework_TestCase
{
    private $_pdfFile2Table;

    public function __construct() {
        $this->_pdfFile2Table = new PdfFile2Table(__DIR__ . "/Res/menu40.pdf");
        $this->_pdfFile2Table->parse();
    }

    public function testSpanned() {
        $page = $this->_pdfFile2Table->getPages()[0];
        //$page->drawPage('colormenu.png');
        $table = $page->getTable();
        $table->drawTable('color.png');
        $this->assertTrue($table->getCell(0, 1) == $table->getCell(0, 2));
    }

    public function testTextReading() {
        $page = $this->_pdfFile2Table->getPages()[0];
        $page->drawPage('menu40.png');
        $table = $page->getTable();
        $this->assertEquals("40", $table->getCell(0, 0)->getTexts()[0]);
        $this->assertEquals("Déjeuner", $table->getCell(0, 1)->getTexts()[0]);
        $this->assertEquals("Dîner", $table->getCell(0, 3)->getTexts()[0]);
        $this->assertEquals("Lundi 29", $table->getCell(1, 0)->getTexts()[0]);
        $this->assertEquals("Feuilleté dubarry", $table->getCell(1, 1)->getTexts()[0]);
        $this->assertEquals("Céléri rémoulade", $table->getCell(1, 1)->getTexts()[1]);
        $this->assertEquals("Betteraves-maïs", $table->getCell(1, 1)->getTexts()[2]);
        $this->assertEquals("Curry d’agneau", $table->getCell(1, 2)->getTexts()[0]);
    }
    // test if no table, array of lines empty, ...
}