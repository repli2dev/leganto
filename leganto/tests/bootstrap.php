<?php
/**
 * Check PHPUnit version (3.5.0 or higher required).
 * New versions load PHPUnit framework automatically.
 */
if (!class_exists('PHPUnit_Framework_TestCase') /*|| (float) PHPUnit_Runner_Version::id() < 3.5*/) {
	if (@include_once 'PHPUnit/Framework.php') {
		die(sprintf("\nPHPUnit 3.5.0 or higher required, you have %s.\n", PHPUnit_Runner_Version::id()));
	} else {
		die("\nPHPUnit 3.5.0 or higher required, none installed.\n");
	}
}

/**
 * Nette Framework (with NetteLoader).
 */
define('APP_DIR', __DIR__);
define('LIBS_DIR', __DIR__ . '/../libs');

require_once LIBS_DIR . '/Nette/loader.php';

$loader = new RobotLoader();
$loader->addDirectory(__DIR__ . '/../app');
$loader->addDirectory(LIBS_DIR);
$loader->addDirectory(__DIR__);
$loader->register();

Environment::loadConfig();
Environment::getSession()->start();

dibi::connect(Environment::getConfig('database'));

/**
 * Nette\Debug error handling.
 */
Debug::enable();

/**
 * Miscellanous PHP settings
 */
date_default_timezone_set('Europe/Prague');

/**
 * Load TestCase class
 */
require_once(__DIR__ . '/TestCase.php');