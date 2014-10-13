<?php

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL | E_STRICT);

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Maximethebault\Pdf2Table\Tests', __DIR__);
$loader->register();