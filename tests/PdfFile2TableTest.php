<?php

namespace Maximethebault\Pdf2Table\Tests;

use Maximethebault\Pdf2Table\PdfFile2Table;

class PdfFile2TableTest extends \PHPUnit_Framework_TestCase
{
    private $_pdfFile;

    public function __construct() {
        $this->_pdfFile = new PdfFile2Table(__DIR__ . "/Res/menu40.pdf");
        $this->_pdfFile->parse();
        $this->_pdfFile2 = new PdfFile2Table(__DIR__ . "/Res/menu42.pdf");
        $this->_pdfFile2->parse();
    }

    public function testSpanned() {
        $page = $this->_pdfFile->getPages()[0];
        //$page->drawPage('colormenu.png');
        $table = $page->getTable();
        $table->drawTable('color.png');
        $this->assertTrue($table->getCell(0, 1) == $table->getCell(0, 2));
    }

    public function testTextReading() {
        $page = $this->_pdfFile->getPages()[0];
        $page->drawPage('menu40.png');
        $table = $page->getTable();
        $this->assertEquals("40", $table->getCell(0, 0)->getTextline()[0]->getText());
        $this->assertEquals("Déjeuner", $table->getCell(0, 1)->getTextline()[0]->getText());
        $this->assertEquals("Dîner", $table->getCell(0, 3)->getTextline()[0]->getText());
        $this->assertEquals("Lundi 29", $table->getCell(1, 0)->getTextline()[0]->getText());
        $this->assertEquals("Feuilleté dubarry", $table->getCell(1, 1)->getTextline()[0]->getText());
        $this->assertEquals("Céléri rémoulade", $table->getCell(1, 1)->getTextline()[1]->getText());
        $this->assertEquals("Betteraves-maïs", $table->getCell(1, 1)->getTextline()[2]->getText());
        $this->assertEquals("Curry d’agneau", $table->getCell(1, 2)->getTextline()[0]->getText());
    }

    public function testTextReadingDiffTable() {
        $page = $this->_pdfFile2->getPages()[0];
        $page->drawPage('menu42.png');
        $table = $page->getTable();
        $this->assertEquals("42", $table->getCell(0, 0)->getTextline()[0]->getText());
        $this->assertEquals("Déjeuner", $table->getCell(0, 1)->getTextline()[0]->getText());
        $this->assertEquals("Dîner", $table->getCell(0, 3)->getTextline()[0]->getText());
        $this->assertEquals("Lundi 13", $table->getCell(1, 0)->getTextline()[0]->getText());
    }
}