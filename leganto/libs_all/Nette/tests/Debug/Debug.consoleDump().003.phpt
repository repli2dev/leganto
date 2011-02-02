<?php

/**
 * Test: Debug::consoleDump() in production mode.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = FALSE;
Debug::$productionMode = TRUE;

header('Content-Type: text/html');

function shutdown() {
	Assert::same('', ob_get_clean());
}
Assert::handler('shutdown');
