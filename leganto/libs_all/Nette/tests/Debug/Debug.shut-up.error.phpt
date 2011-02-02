<?php

/**
 * Test: Debug errors and shut-up operator.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = TRUE;
Debug::$productionMode = FALSE;

Debug::enable();

function shutdown() {
	Assert::match("exception 'FatalErrorException' with message 'Call to undefined function missing_funcion()' in %a%:%d%
Stack trace:
#0 [internal function]: %ns%Debug::_shutdownHandler()
#1 {main}
Report generated at %a%
PHP %a%
Nette Framework %a%", ob_get_clean());
	die(0);
}
Assert::handler('shutdown');



@missing_funcion();
