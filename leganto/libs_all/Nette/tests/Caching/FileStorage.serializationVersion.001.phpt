<?php

/**
 * Test: FileStorage @serializationVersion dependency test.
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


class Foo
{
}


// Writing cache...
$cache->save($key, new Foo);

Assert::true( isset($cache[$key]), 'Is cached?' );
