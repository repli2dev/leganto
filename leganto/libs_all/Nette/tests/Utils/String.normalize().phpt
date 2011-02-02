<?php

/**
 * Test: String::normalize()
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( "Hello\n  World",  String::normalize("\r\nHello  \r  World \n\n") );
