<?php

/**
 * Test: BaseTemplate::optimizePhp()
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$input = file_get_contents(dirname(__FILE__) . '/templates/optimize.phtml');
$expected = file_get_contents(dirname(__FILE__) . '/BaseTemplate.optimizePhp().expect');
Assert::match($expected, BaseTemplate::optimizePhp($input));
