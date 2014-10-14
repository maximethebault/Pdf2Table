<?php

namespace Maximethebault\Pdf2Table;

class HorizontalLine extends Line
{

    /**
     * Get the distance between two lines
     *
     * @param $line HorizontalLine the line we want to measure the distance with
     *
     * @return float the distance between the lines, 0 if one starts after the other ends
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
     * Merges two lines
     *
     * @param $line HorizontalLine
     */
    protected function merge($line) {
    }
}