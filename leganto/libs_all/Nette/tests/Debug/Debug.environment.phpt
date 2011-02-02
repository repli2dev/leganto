<?php

/**
 * Test: Debug and Environment.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Debug::$consoleMode = FALSE;



Assert::null( Debug::$productionMode );

// setting production environment...

Environment::setMode('production', TRUE);
Debug::enable();

Assert::true( Debug::$productionMode );
