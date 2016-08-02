<?php

// Application context. Can be devel or production but also define your contexts
$context = "devel";

// ************************************ //
// * NO NEED TO EDIT BELOW THIS POINT * //
// ************************************ //

// Start session
session_start();

// Include context information
require_once $context . ".php";

// Constants:
define ('DS', DIRECTORY_SEPARATOR);

// Get site path
$site_path = realpath(dirname(__FILE__) . DS . '..' . DS) . DS;
define ('SITE_PATH', $site_path);

// Environment checkings
if (version_compare(phpversion(), '5.1.0', '<') == true) {
	die ('PHP 5.1 or higher required!');
}

// Check temp dir permissions
define ('TMP_DIR', SITE_PATH . 'tmp' . DS );
if (!is_writable(TMP_DIR)) {
	throw new SpException('tmp directory '.TMP_DIR.' is not writable, check permissions');
}

// Check cache dir permissions
define ('CACHE_DIR', TMP_DIR);
if (!is_writable(CACHE_DIR)) {
	throw new SpException('Cache directory is not writable, check permissions');
}

// Check log file permissions
define ('LOG_DIR', SITE_PATH . 'logs' . DS);
define ('LOG_FILE', LOG_DIR . $context . '.log');

if (!file_exists(LOG_FILE)) {
	throw new SpException('Log file not found', 10);
}

if (!is_writable(LOG_FILE)) {
	throw new SpException('Logs file not writable, check permissions', 11);
}

/**********************
 *
 * Set default timezone for all date/time functions
 * Link to all supported timezones: http://php.net/manual/en/timezones.php
 *
 * ********************/
date_default_timezone_set(TIMEZONE);

// For loading classes
function autoload($class_name)
{
	$filename = str_replace('_', DS, $class_name) . '.php';
	$file = SITE_PATH . 'model' . DS . $filename;

	if (file_exists($file) == false) {
		return false;
	}

	include $file;
}

spl_autoload_extensions(".php"); // Only autoload .php files
spl_autoload_register('autoload');
?>
