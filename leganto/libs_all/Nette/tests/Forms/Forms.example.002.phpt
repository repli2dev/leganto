<?php

/**
 * Test: Forms example.
 *
 * @author     David Grudl
 * @package    Nette\Forms
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



ob_start();
require '../../examples/forms/CSRF-protection.php';
Assert::match( file_get_contents(dirname(__FILE__) . '/Forms.example.002.expect'), ob_get_clean() );
