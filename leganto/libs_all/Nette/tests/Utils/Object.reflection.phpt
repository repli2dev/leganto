<?php

/**
 * Test: Object reflection.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Object.inc';



$obj = new TestClass;
Assert::same( 'TestClass', $obj->getReflection()->getName() );
Assert::same( 'TestClass', $obj->Reflection->getName() );
