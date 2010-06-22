<?php

/**
 * Test: Nette\Security\Permission Ensures that removing non-existent default allow rule does nothing.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$acl = new Permission;
$acl->removeAllow();
Assert::false( $acl->isAllowed() );
