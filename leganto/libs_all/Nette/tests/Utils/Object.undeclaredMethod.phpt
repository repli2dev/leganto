<?php

/**
 * Test: Object undeclared method.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Object.inc';



try {
	$obj = new TestClass;
	$obj->undeclared();

	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', 'Call to undefined method TestClass::undeclared().', $e );
}
