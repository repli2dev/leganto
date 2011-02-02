<?php

/**
 * Test: String::trim()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( 'x',  String::trim(" \t\n\r\x00\x0B\xC2\xA0x") );
Assert::same( 'a b',  String::trim(' a b ') );
Assert::same( ' a b ',  String::trim(' a b ', '') );
Assert::same( 'e',  String::trim("\xc5\x98e-", "\xc5\x98-") ); // Ře-
