<?php

/**
 * Test: Debug::consoleDump() in non-HTML mode.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = FALSE;
Debug::$productionMode = FALSE;
header('Content-Type: text/plain');

Debug::enable();

function shutdown() {
	Assert::same('', ob_get_clean());
}
Assert::handler('shutdown');
