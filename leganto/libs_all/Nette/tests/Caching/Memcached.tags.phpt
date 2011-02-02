<?php

/**
 * Test: MemcachedStorage tags dependency test.
 *
 * @author     David Grudl
 * @package    Nette\Caching
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';


if (!MemcachedStorage::isAvailable()) {
	TestHelpers::skip('Requires PHP extension Memcache.');
}


// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
Environment::setVariable('tempDir', TEMP_DIR);
TestHelpers::purge(TEMP_DIR);



$storage = new MemcachedStorage('localhost');
$cache = new Cache($storage);


// Writing cache...
$cache->save('key1', 'value1', array(
	Cache::TAGS => array('one', 'two'),
));

$cache->save('key2', 'value2', array(
	Cache::TAGS => array('one', 'three'),
));

$cache->save('key3', 'value3', array(
	Cache::TAGS => array('two', 'three'),
));

$cache['key4'] = 'value4';


// Cleaning by tags...
$cache->clean(array(
	Cache::TAGS => 'one',
));

Assert::false( isset($cache['key1']), 'Is cached key1?' );
Assert::false( isset($cache['key2']), 'Is cached key2?' );
Assert::true( isset($cache['key3']), 'Is cached key3?' );
Assert::true( isset($cache['key4']), 'Is cached key4?' );
