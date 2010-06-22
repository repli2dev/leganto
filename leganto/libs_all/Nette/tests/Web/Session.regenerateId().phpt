<?php

/**
 * Test: Nette\Web\Session::regenerateId()
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$session = new Session;
$session->start();
$oldId = $session->getId();
$session->regenerateId();
$newId = $session->getId();
dump( $newId != $oldId );



__halt_compiler();

------EXPECT------
bool(TRUE)
