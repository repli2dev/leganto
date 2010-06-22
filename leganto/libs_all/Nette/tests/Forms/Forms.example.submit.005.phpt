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
$_POST = array('num1'=>'5','num2'=>'5','submit1'=>'Send',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;

require '../../examples/forms/custom-validator.php';



__halt_compiler();
