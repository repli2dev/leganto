<?php

/**
 * Test: FileStorage & namespace test.
 *
 * @author     David Grudl
 * @package    Nette\Caching
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
TestHelpers::purge(TEMP_DIR);


$storage = new FileStorage(TEMP_DIR);
$cacheA = new Cache($storage, 'a');
$cacheB = new Cache($storage, 'b');


// Writing cache...
$cacheA['key'] = 'hello';
$cacheB['key'] = 'world';

Assert::true( isset($cacheA['key']), 'Is cached #1?' );
Assert::true( isset($cacheB['key']), 'Is cached #2?' );
Assert::true( $cacheA['key'] === 'hello', 'Is cache ok #1?' );
Assert::true( $cacheB['key'] === 'world', 'Is cache ok #2?' );


// Removing from cache #2 using unset()...
unset($cacheB['key']);
$cacheA->release();
$cacheB->release();

Assert::true( isset($cacheA['key']), 'Is cached #1?' );
Assert::false( isset($cacheB['key']), 'Is cached #2?' );
