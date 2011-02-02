<?php

/**
 * Test: Config readonly.
 *
 * @author     David Grudl
 * @package    Nette\Config
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$config = Config::fromFile('config1.ini', 'development', NULL);

try {
	$config->freeze();
	$config->database->adapter = 'new value';
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Cannot modify a frozen object '%ns%Config'.", $e );
}

try {
	$dolly = clone $config;
	$dolly->database->adapter = 'works good';
	unset($dolly);
} catch (Exception $e) {
	Assert::fail('Expected exception');
}
