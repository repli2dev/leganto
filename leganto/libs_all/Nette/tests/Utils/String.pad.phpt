<?php

/**
 * Test: String::padLeft() & padRight()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( "ŤOUŤOUŤŽLU", String::padLeft("\xc5\xbdLU", 10, "\xc5\xa4OU") );
Assert::same( "ŤOUŤOUŽLU", String::padLeft("\xc5\xbdLU", 9, "\xc5\xa4OU") );
Assert::same( "ŽLU", String::padLeft("\xc5\xbdLU", 3, "\xc5\xa4OU") );
Assert::same( "ŽLU", String::padLeft("\xc5\xbdLU", 0, "\xc5\xa4OU") );
Assert::same( "ŽLU", String::padLeft("\xc5\xbdLU", -1, "\xc5\xa4OU") );
Assert::same( "ŤŤŤŤŤŤŤŽLU", String::padLeft("\xc5\xbdLU", 10, "\xc5\xa4") );
Assert::same( "ŽLU", String::padLeft("\xc5\xbdLU", 3, "\xc5\xa4") );
Assert::same( "       ŽLU", String::padLeft("\xc5\xbdLU", 10) );



Assert::same( "ŽLUŤOUŤOUŤ", String::padRight("\xc5\xbdLU", 10, "\xc5\xa4OU") );
Assert::same( "ŽLUŤOUŤOU", String::padRight("\xc5\xbdLU", 9, "\xc5\xa4OU") );
Assert::same( "ŽLU", String::padRight("\xc5\xbdLU", 3, "\xc5\xa4OU") );
Assert::same( "ŽLU", String::padRight("\xc5\xbdLU", 0, "\xc5\xa4OU") );
Assert::same( "ŽLU", String::padRight("\xc5\xbdLU", -1, "\xc5\xa4OU") );
Assert::same( "ŽLUŤŤŤŤŤŤŤ", String::padRight("\xc5\xbdLU", 10, "\xc5\xa4") );
Assert::same( "ŽLU", String::padRight("\xc5\xbdLU", 3, "\xc5\xa4") );
Assert::same( "ŽLU       ", String::padRight("\xc5\xbdLU", 10) );
