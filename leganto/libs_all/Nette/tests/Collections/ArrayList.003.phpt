<?php

/**
 * Test: ArrayList::insertAt()
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

$larry = new Person('Larry');

Assert::true( $list->insertAt(0, $larry) );
Assert::equal( array(
	new Person("Larry"),
	new Person("Jack"),
	new Person("Mary"),
), (array) $list );

Assert::true( $list->insertAt(3, $larry) );
Assert::equal( array(
	new Person("Larry"),
	new Person("Jack"),
	new Person("Mary"),
	new Person("Larry"),
), (array) $list );

try {
	$list->insertAt(6, $larry);
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('ArgumentOutOfRangeException', '', $e );
}
