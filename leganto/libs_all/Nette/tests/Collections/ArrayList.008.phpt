<?php

/**
 * Test: ArrayList and extension method.
 *
 * @author     David Grudl
 * @package    Nette\Collections
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Collections.inc';



function ICollection_prototype_join(ICollection $that, $separator)
{
	return implode($separator, (array) $that);
}$list = new ArrayList(NULL, 'Person');

$list[] = new Person('Jack');
$list[] = new Person('Mary');
$list[] = new Person('Larry');

Assert::same( "Jack, Mary, Larry", $list->join(', ') );

// undeclared method
try {
	$list->test();
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('MemberAccessException', "Call to undefined method %ns%ArrayList::test().", $e );
}
