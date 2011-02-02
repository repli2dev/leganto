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
$_POST = array('first'=>array('name'=>'James Bond','email'=>'bond@007.com','street'=>'Unknown','city'=>'London','country'=>'GB',),'second'=>array('name'=>'Jim Beam','email'=>'jim@beam.com','street'=>'','city'=>'','country'=>'US',),'submit1'=>'Send',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;

ob_start();
require '../../examples/forms/naming-containers.php';
Assert::match( file_get_contents(dirname(__FILE__) . '/Forms.example.submit.006.expect'), ob_get_clean() );
