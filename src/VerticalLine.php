<?php

namespace Maximethebault\Pdf2Table;

class VerticalLine extends Line
{
    /**
     * @inheritdoc
     */
    protected function distance($line) {
        if($this->_border->getYStart() > $line->_border->getYStart()) {
            if($this->_border->getYStart() < $line->_border->getYEnd()) {
                return 0;
            }
            else {
                return $this->_border->getYStart() - $line->_border->getYEnd();
            }
        }
        else {
            if($line->_border->getYStart() < $this->_border->getYEnd()) {
                return 0;
            }
            else {
                return $line->_border->getYStart() - $this->_border->getYEnd();
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function merge($line) {
        $this->_border->setYStart(min($this->_border->getYStart(), $line->_border->getYStart()));
        $this->_border->setYEnd(max($this->_border->getYEnd(), $line->_border->getYEnd()));
    }
} 