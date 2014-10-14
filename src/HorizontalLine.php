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
        if($this->getXStart() > $line->getXStart()) {
            if($this->getXStart() < $line->getXEnd()) {
                return 0;
            }
            else {
                return $this->getXStart() - $line->getXEnd();
            }
        }
        else {
            if($line->getXStart() < $this->getXEnd()) {
                return 0;
            }
            else {
                return $line->getXStart() - $this->getXEnd();
            }
        }
    }

    /**
     * Merges two lines, and stores the resulting line in $this
     *
     * @param $line HorizontalLine
     */
    protected function merge($line) {
        $this->_xStart = min($this->_xStart, $line->_xStart);
        $this->_xEnd = max($this->_xEnd, $line->_xEnd);
    }
}