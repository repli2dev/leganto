<?php

/**
 * Test: Object reference to property.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Object.inc';



$obj = new TestClass;
$obj->foo = 'hello';
@$x = & $obj->foo;
$x = 'changed by reference';
Assert::same( 'hello', $obj->foo );
