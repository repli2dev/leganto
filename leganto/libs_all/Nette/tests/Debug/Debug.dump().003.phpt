<?php

/**
 * Test: Debug::dump() in production mode.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = FALSE;
Debug::$productionMode = TRUE;


ob_start();
Debug::dump('sensitive data');
Assert::same( '', ob_get_clean() );

Assert::match( '<pre class="dump">"forced" (6)
</pre>', Debug::dump('forced', TRUE) );
