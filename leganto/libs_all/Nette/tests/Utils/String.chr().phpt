<?php

/**
 * Test: String::chr()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( "\x00",  String::chr(0), '#0' );
Assert::same( "\xc3\xbf",  String::chr(255), '#255 in UTF-8' );
Assert::same( "\xFF",  String::chr(255, 'ISO-8859-1'), '#255 in ISO-8859-1' );
