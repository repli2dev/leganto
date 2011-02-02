<?php

/**
 * Test: TemplateCacheStorage test.
 *
 * @author     David Grudl
 * @package    Nette\Caching
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$key = 'nette';
$value = '<?php echo "Hello World" ?>';

// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
TestHelpers::purge(TEMP_DIR);



$cache = new Cache(new TemplateCacheStorage(TEMP_DIR));


Assert::false( isset($cache[$key]), 'Is cached?' );

Assert::null( $cache[$key], 'Cache content' );

// Writing cache...
$cache[$key] = $value;

$cache->release();

Assert::true( isset($cache[$key]), 'Is cached?' );

Assert::true( (bool) preg_match('#nette\.php$#', $cache[$key]['file']) );
Assert::true( is_resource($cache[$key]['handle']) );

$var = $cache[$key];

// Test include

// this is impossible
// $cache[$key] = NULL;

ob_start();
include $var['file'];
Assert::same( 'Hello World', ob_get_clean() );

fclose($var['handle']);
