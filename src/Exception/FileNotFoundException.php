<?php

namespace Maximethebault\Pdf2Table\Exception;

class FileNotFoundException extends \Exception
{
    /**
     * Path to the non-existing file
     *
     * @var string
     */
    private $_path;

    public function __construct($path, $message = '', $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->_path = $path;
    }

    /**
     * Getter for $_path
     *
     * @return string
     *
     * @see $_path
     */
    public function getPath() {
        return $this->_path;
    }
} 