<?php

/**
 * Test: Permission Ensures that assertions on privileges work properly.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/MockAssertion.inc';



$acl = new Permission;
$acl->allow(NULL, NULL, 'somePrivilege', new MockAssertion(TRUE));
Assert::true( $acl->isAllowed(NULL, NULL, 'somePrivilege') );

$acl->allow(NULL, NULL, 'somePrivilege', new MockAssertion(FALSE));
Assert::false( $acl->isAllowed(NULL, NULL, 'somePrivilege') );
