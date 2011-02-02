<?php

/**
 * Test: FileStorage callbacks dependency.
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


function dependency($val)
{
	return $val;
}


// Writing cache...
$cache->save($key, $value, array(
	Cache::CALLBACKS => array(array('dependency', 1)),
));
$cache->release();

Assert::true( isset($cache[$key]), 'Is cached?' );



// Writing cache...
$cache->save($key, $value, array(
	Cache::CALLBACKS => array(array('dependency', 0)),
));
$cache->release();

Assert::false( isset($cache[$key]), 'Is cached?' );
