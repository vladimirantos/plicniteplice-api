<?php

// Load .env file, if exists
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

// Load default settings
$settings = require __DIR__ . '/defaults.php';

return $settings;
