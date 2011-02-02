<?php

/**
 * Test: NetteLoader basic usage.
 *
 * @author     David Grudl
 * @package    Nette\Loaders
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$loader = NetteLoader::getInstance();
$loader->register();

Assert::true( class_exists('Debug'), 'Class Debug loaded?' );
