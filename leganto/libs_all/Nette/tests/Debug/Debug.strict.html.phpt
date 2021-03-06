<?php

/**
 * Test: Debug notices and warnings with $strictMode in HTML.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 * @assertCode 500
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = FALSE;
Debug::$productionMode = FALSE;
header('Content-Type: text/html');

Debug::$strictMode = TRUE;
Debug::enable();

function shutdown() {
	Assert::match(file_get_contents(dirname(__FILE__) . '/Debug.strict.html.expect'), ob_get_clean());
}
Assert::handler('shutdown');



function first($arg1, $arg2)
{
	second(TRUE, FALSE);
}


function second($arg1, $arg2)
{
	third(array(1, 2, 3));
}


function third($arg1)
{
	$x++;
}


first(10, 'any string');

// after



__halt_compiler() ?>

---EXPECTHEADERS---
Status: 500 Internal Server Error
