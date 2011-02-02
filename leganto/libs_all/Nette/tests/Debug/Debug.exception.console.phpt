<?php

/**
 * Test: Debug exception in console.
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
	Assert::match("exception 'Exception' with message 'The my exception' in %a%
Stack trace:
#0 %a%: third(Array)
#1 %a%: second(true, false)
#2 %a%: first(10, 'any string')
#3 {main}
Report generated at %a%
PHP %a%
Nette Framework %a%", ob_get_clean());
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
	throw new Exception('The my exception', 123);
}


first(10, 'any string');
