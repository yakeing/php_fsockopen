<?php
ddd
error_reporting(E_ALL | E_STRICT);
$loader = include __DIR__ . '/../vendor/autoload.php';
$loader->add('php_jwsign', __DIR__ . '/../src');