<?php

/**
 * Test: Debug::dump() with $showLocation.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = FALSE;
Debug::$productionMode = FALSE;



Debug::$showLocation = TRUE;

ob_start();
Debug::dump('xxx');
Assert::match( '<pre class="dump">"xxx" (3) <small>in file %a% on line %d%</small>
</pre>', ob_get_clean() );
