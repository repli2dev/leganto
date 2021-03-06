<?php

/**
 * Test: String::endsWith()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::true( String::endsWith('123', NULL), "endsWith('123', NULL)" );
Assert::true( String::endsWith('123', ''), "endsWith('123', '')" );
Assert::true( String::endsWith('123', '3'), "endsWith('123', '3')" );
Assert::false( String::endsWith('123', '2'), "endsWith('123', '2')" );
Assert::true( String::endsWith('123', '123'), "endsWith('123', '123')" );
Assert::false( String::endsWith('123', '1234'), "endsWith('123', '1234')" );
