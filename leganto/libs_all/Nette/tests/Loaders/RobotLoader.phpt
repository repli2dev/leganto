<?php

/**
 * Test: RobotLoader basic usage.
 *
 * @author     David Grudl
 * @package    Nette\Loaders
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
TestHelpers::purge(TEMP_DIR);
Environment::setVariable('tempDir', TEMP_DIR);


$loader = new RobotLoader;
$loader->addDirectory('../../Nette/');
$loader->addDirectory(dirname(__FILE__));
$loader->addDirectory(dirname(__FILE__)); // purposely doubled
$loader->register();

Assert::false( class_exists('ConditionalClass') );
Assert::true( class_exists('TestClass') );
Assert::true( class_exists('MySpace1\TestClass') );
Assert::true( class_exists('MySpace2\TestClass') );
