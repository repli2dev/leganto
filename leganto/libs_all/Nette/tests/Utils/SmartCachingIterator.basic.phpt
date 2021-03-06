<?php

/**
 * Test: SmartCachingIterator basic usage.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// ==> Two items in array

$arr = array('Nette', 'Framework');

$iterator = new SmartCachingIterator($arr);
$iterator->rewind();
Assert::true( $iterator->valid() );
Assert::true( $iterator->isFirst() );
Assert::false( $iterator->isLast() );
Assert::same( 1, $iterator->getCounter() );

$iterator->next();
Assert::true( $iterator->valid() );
Assert::false( $iterator->isFirst() );
Assert::true( $iterator->isLast() );
Assert::same( 2, $iterator->getCounter() );

$iterator->next();
Assert::false( $iterator->valid() );

$iterator->rewind();
Assert::true( $iterator->isFirst() );
Assert::false( $iterator->isLast() );
Assert::same( 1, $iterator->getCounter() );
Assert::false( $iterator->isEmpty() );



$arr = array('Nette');

$iterator = new SmartCachingIterator($arr);
$iterator->rewind();
Assert::true( $iterator->valid() );
Assert::true( $iterator->isFirst() );
Assert::true( $iterator->isLast() );
Assert::same( 1, $iterator->getCounter() );

$iterator->next();
Assert::false( $iterator->valid() );

$iterator->rewind();
Assert::true( $iterator->isFirst() );
Assert::true( $iterator->isLast() );
Assert::same( 1, $iterator->getCounter() );
Assert::false( $iterator->isEmpty() );



$arr = array();

$iterator = new SmartCachingIterator($arr);
$iterator->next();
$iterator->next();
Assert::false( $iterator->isFirst() );
Assert::true( $iterator->isLast() );
Assert::same( 0, $iterator->getCounter() );
Assert::true( $iterator->isEmpty() );
