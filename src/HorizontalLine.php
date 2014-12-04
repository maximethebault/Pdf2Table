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
        if($this->_border->getWidth() < $line->_border->getWidth()) {
            $this->_border->setYStart($line->_border->getYStart());
            $this->_border->setYEnd($line->_border->getYStart());
        }
        else {
            $this->_border->setYEnd($this->_border->getYStart());
        }
        $this->_border->setXStart(min($this->_border->getXStart(), $line->_border->getXStart()));
        $this->_border->setXEnd(max($this->_border->getXEnd(), $line->_border->getXEnd()));
    }

    /**
     * @inheritdoc
     */
    public function getLevel() {
        return min($this->_border->getYStart(), $this->_border->getYEnd());
    }

    /**
     * @inheritdoc
     */
    public function getStartPoint() {
        return min($this->_border->getXStart(), $this->_border->getXEnd());
    }

    /**
     * @inheritdoc
     */
    public function getEndPoint() {
        return max($this->_border->getXStart(), $this->_border->getXEnd());
    }
}