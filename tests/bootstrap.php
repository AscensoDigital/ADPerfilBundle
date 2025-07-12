<?php

// Carga el autoload estándar
require_once __DIR__ . '/../vendor/autoload.php';

// Define KERNEL_DIR para evitar RuntimeException
if (!defined('KERNEL_DIR')) {
    define('KERNEL_DIR', __DIR__.'/app');
}
