<?php

/**
 * Test: DummyStorage test.
 *
 * @author     David Grudl
 * @package    Nette\Caching
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// key and data with special chars
$key = 'nette';
$value = '"Hello World"';

$cache = new Cache(new DummyStorage, 'myspace');


Assert::false( isset($cache[$key]), 'Is cached?' );

Assert::null( $cache[$key], 'Cache content:' );


// Writing cache...
$cache[$key] = $value;
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );

Assert::false( $cache[$key] === $value, 'Is cache ok?' );


// Removing from cache using unset()...
unset($cache[$key]);
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );


// Removing from cache using set NULL...
$cache[$key] = $value;
$cache[$key] = NULL;
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );
