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
$_POST = array('name'=>'�lu&#357;ou&#269;k� k&#367;&#328;','country'=>array(0=>'&#268;esk� republika',1=>'SlovakiaXX',2=>'Canada',),'note'=>'&#1078;&#1077;&#1076;','submit1'=>'Send','userid'=>'k&#367;&#328;',);
Debug::$productionMode = FALSE;
Debug::$consoleMode = TRUE;


ob_start();
require '../../examples/forms/custom-encoding.php';
Assert::match( file_get_contents(dirname(__FILE__) . '/Forms.example.submit.003.expect'), ob_get_clean() );
