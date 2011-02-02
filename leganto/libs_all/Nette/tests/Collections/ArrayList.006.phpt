<?php

/**
 * Test: ArrayList::__construct()
 *
 * @author     David Grudl
 * @package    Nette\Collections
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Collections.inc';



$arr = array(
	'a' => new Person('Jack'),
	'b' => new Person('Mary'),
	'c' => new ArrayObject(),
);

try {
	// Construct from array
	$list = new ArrayList($arr, 'Person');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidArgumentException', "Item must be 'Person' object.", $e );
}
