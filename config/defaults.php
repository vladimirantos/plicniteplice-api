<?php

// Application default settings


use Monolog\Logger;

error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Europe/Berlin');

$settings = [];

// Error handler
$settings['error'] = [
    // Should be set to false for the production environment
    'display_error_details' => false,
    // Should be set to false for the test environment
    'log_errors' => true,
    // Display error details in error log
    'log_error_details' => true,
];

// Logger settings
$settings['logger'] = [
	'name' => 'app',
	'path' => __DIR__ . '/../logs/app.log',
	'level' => Logger::DEBUG,
];

$settings['mysql'] = [
	'host' => $_ENV['MYSQL_HOST'],
	'user' => $_ENV['MYSQL_USER'],
	'password' => $_ENV['MYSQL_PASSWORD'],
	'db_name' => $_ENV['MYSQL_DB_NAME'],
];

$settings['email'] = [
	'host' => 'mail.rozhled.cz',
	'port' => 465,
	'smtpauth' => 'true',
	'smtpsecure' => 'ssl',
	'username' => 'robot@jdulovit.cz',
	'password' => 'V8AtEQRC'
];

return $settings;
