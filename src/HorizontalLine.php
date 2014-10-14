<?php

namespace Maximethebault\Pdf2Table;

class HorizontalLine extends Line
{
    /**
     * @inheritdoc
     */
    protected function distance($line) {
        if($this->_border->getXStart() > $line->_border->getXStart()) {
            if($this->_border->getXStart() < $line->_border->getXEnd()) {
                return 0;
            }
            else {
                return $this->_border->getXStart() - $line->_border->getXEnd();
            }
        }
        else {
            if($line->_border->getXStart() < $this->_border->getXEnd()) {
                return 0;
            }
            else {
                return $line->_border->getXStart() - $this->_border->getXEnd();
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function merge($line) {
        $this->_border->setXStart(min($this->_border->getXStart(), $line->_border->getXStart()));
        $this->_border->setXEnd(max($this->_border->getXEnd(), $line->_border->getXEnd()));
    }
}