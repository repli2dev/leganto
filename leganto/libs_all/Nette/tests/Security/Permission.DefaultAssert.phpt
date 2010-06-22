<?php

/**
 * Test: Nette\Security\Permission Ensures that the default rule obeys its assertion.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/MockAssertion.inc';



$acl = new Permission;
$acl->deny(NULL, NULL, NULL, new MockAssertion(FALSE));
Assert::true( $acl->isAllowed(NULL, NULL, 'somePrivilege') );
