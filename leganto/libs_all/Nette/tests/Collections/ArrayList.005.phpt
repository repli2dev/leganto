<?php

/**
 * Test: ArrayList and removing items.
 *
 * @author     David Grudl
 * @package    Nette\Collections
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Collections.inc';



$list = new ArrayList(NULL, 'Person');
$list[] = new Person('Jack');
$list[] = new Person('Mary');
$list[] = $larry = new Person('Larry');


Assert::true( $list->remove($larry), "Removing Larry" );

Assert::false( $list->remove($larry), "Removing Larry second time" );


try {
	// unset -1
	unset($list[-1]);
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('ArgumentOutOfRangeException', '', $e );
}

// unset 1
unset($list[1]);
