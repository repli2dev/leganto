<?php

/**
 * Test: Uri malformed URI.
 *
 * @author     David Grudl
 * @package    Nette\Web
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



try {
	$uri = new Uri(':');

	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidArgumentException', "Malformed or unsupported URI ':'.", $e );
}
