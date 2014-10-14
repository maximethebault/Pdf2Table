<?php

namespace Maximethebault\Pdf2Table;

abstract class Line extends Border
{
    /**
     * Glue two lines together, if they're close enough
     *
     * @param $line HorizontalLine the line we want to merge with
     *
     * @return bool true if the lines were glued together
     */
    public function glue($line) {
        if($this->distance($line) < 2) {
            $this->merge($line);
        }
    }

    /**
     * Get the distance between two lines
     *
     * @param $line HorizontalLine the line we want to measure the distance with
     *
     * @return float the distance between the lines, 0 if one starts after the other ends
     */
    abstract protected function distance($line);

    /**
     * Merges two lines
     *
     * @param $line HorizontalLine
     */
    abstract protected function merge($line);
} 