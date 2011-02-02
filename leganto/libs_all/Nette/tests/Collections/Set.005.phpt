<?php

/**
 * Test: Set::contains()
 *
 * @author     David Grudl
 * @package    Nette\Collections
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Collections.inc';



$set = new Set(NULL, 'Person');
$set->append($jack = new Person('Jack'));
$set->append(new Person('Mary'));
$larry = new Person('Larry');
$foo = new ArrayObject;

Assert::true( $set->contains($jack), "Contains Jack?" );

Assert::false( $set->contains($larry), "Contains Larry?" );

Assert::false( $set->contains($foo), "Contains foo?" );
