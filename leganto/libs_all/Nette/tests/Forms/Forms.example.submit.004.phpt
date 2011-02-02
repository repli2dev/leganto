<?php

/**
 * Test: Forms example.
 *
 * @author     David Grudl
 * @package    Nette\Forms
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$disableExit = TRUE;
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = array('name'=>'John Doe ','age'=>'9.9','email'=>'@','street'=>'','city'=>'Troubsko','country'=>'0','password'=>'xx','password2'=>'xx','note'=>'','submit1'=>'Send','userid'=>'231',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;

ob_start();
require '../../examples/forms/custom-rendering.php';
Assert::match( file_get_contents(dirname(__FILE__) . '/Forms.example.submit.004.expect'), ob_get_clean() );
