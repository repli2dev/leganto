<?php

/**
 * Test: Environment name.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



//define('ENVIRONMENT', 'lab');

Assert::same( 'production', Environment::getName(), 'Name:' );



try {
	// Setting name:
	Environment::setName('lab2');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', 'Environment name has been already set.', $e );
}
