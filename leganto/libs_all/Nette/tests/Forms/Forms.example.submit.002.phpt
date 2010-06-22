<?php

/**
 * Test: Nette\Forms example.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Forms
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$disableExit = TRUE;
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = array('text'=>'a','submit1'=>'Send',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;

require '../../examples/forms/CSRF-protection.php';



__halt_compiler();
