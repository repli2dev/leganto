<?php

/**
 * Test: FileStorage items dependency test.
 *
 * @author     David Grudl
 * @package    Nette\Caching
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$key = 'nette';
$value = 'rulez';

// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
TestHelpers::purge(TEMP_DIR);


$cache = new Cache(new FileStorage(TEMP_DIR));


// Writing cache...
$cache->save($key, $value, array(
	Cache::ITEMS => array('dependent'),
));
$cache->release();

Assert::true( isset($cache[$key]), 'Is cached?' );


// Modifing dependent cached item
$cache['dependent'] = 'hello world';
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );


// Writing cache...
$cache->save($key, $value, array(
	Cache::ITEMS => 'dependent',
));
$cache->release();

Assert::true( isset($cache[$key]), 'Is cached?' );


// Modifing dependent cached item
sleep(2);
$cache['dependent'] = 'hello europe';
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );


// Writing cache...
$cache->save($key, $value, array(
	Cache::ITEMS => 'dependent',
));
$cache->release();

Assert::true( isset($cache[$key]), 'Is cached?' );


// Deleting dependent cached item
$cache['dependent'] = NULL;
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );
