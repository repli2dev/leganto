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
$_POST = array('name'=>'John Doe ','age'=>'9.9','email'=>'@','street'=>'','city'=>'Troubsko','country'=>'0','password'=>'xx','password2'=>'xx','note'=>'','submit1'=>'Send','userid'=>'231',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;

require '../../examples/forms/custom-rendering.php';



__halt_compiler();
