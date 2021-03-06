<?php

namespace Maximethebault\Pdf2Table;

class VerticalLine extends Line
{
    /**
     * @inheritdoc
     */
    public function getLevel() {
        return min($this->_border->getXStart(), $this->_border->getXEnd());
    }

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
        if($this->_border->getHeight() < $line->_border->getHeight()) {
            $this->_border->setXStart($line->_border->getXStart());
            $this->_border->setXEnd($line->_border->getXStart());
        }
        else {
            $this->_border->setXEnd($this->_border->getXStart());
        }
        $this->_border->setYStart(min($this->_border->getYStart(), $line->_border->getYStart()));
        $this->_border->setYEnd(max($this->_border->getYEnd(), $line->_border->getYEnd()));
    }

    /**
     * @inheritdoc
     */
    public function getStartPoint() {
        return min($this->_border->getYStart(), $this->_border->getYEnd());
    }

    /**
     * @inheritdoc
     */
    public function getEndPoint() {
        return max($this->_border->getYStart(), $this->_border->getYEnd());
    }
} 