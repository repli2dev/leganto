<?php

/**
 * Test: FileStorage clean with Cache::ALL
 *
 * @author     Petr Procházka
 * @package    Nette\Caching
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
Environment::setVariable('tempDir', TEMP_DIR);

$storage = new FileStorage(TEMP_DIR);
$cacheA = new Cache($storage);
$cacheB = new Cache($storage,'B');

$cacheA['test1'] = 'David';
$cacheA['test2'] = 'Grudl';
$cacheB['test1'] = 'divaD';
$cacheB['test2'] = 'ldurG';

Assert::same( 'David Grudl divaD ldurG', implode(' ',array(
	$cacheA['test1'],
	$cacheA['test2'],
	$cacheB['test1'],
	$cacheB['test2'],
)));

$storage->clean(array(Cache::ALL => TRUE));

Assert::null( $cacheA['test1'] );

Assert::null( $cacheA['test2'] );

Assert::null( $cacheB['test1'] );

Assert::null( $cacheB['test2'] );
