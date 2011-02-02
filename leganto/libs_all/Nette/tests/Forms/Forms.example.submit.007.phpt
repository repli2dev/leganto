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
$_POST = array('name'=>'John Doe ','age'=>'  12 ','email'=>'@','street'=>'','city'=>'','country'=>'CZ','password'=>'xxx','password2'=>'xxx','note'=>'','userid'=>'231','submit1'=>'Send',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;

ob_start();
require '../../examples/forms/manual-rendering.php';
Assert::match( file_get_contents(dirname(__FILE__) . '/Forms.example.submit.007.expect'), ob_get_clean() );
