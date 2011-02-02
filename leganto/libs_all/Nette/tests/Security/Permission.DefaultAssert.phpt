<?php

/**
 * Test: Permission Ensures that the default rule obeys its assertion.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/MockAssertion.inc';



$acl = new Permission;
$acl->deny(NULL, NULL, NULL, new MockAssertion(FALSE));
Assert::true( $acl->isAllowed(NULL, NULL, 'somePrivilege') );
