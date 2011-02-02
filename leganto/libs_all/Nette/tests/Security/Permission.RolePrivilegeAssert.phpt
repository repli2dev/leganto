<?php

/**
 * Test: Permission Ensures that assertions on privileges work properly for a particular Role.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/MockAssertion.inc';



$acl = new Permission;
$acl->addRole('guest');
$acl->allow('guest', NULL, 'somePrivilege', new MockAssertion(TRUE));
Assert::true( $acl->isAllowed('guest', NULL, 'somePrivilege') );
$acl->allow('guest', NULL, 'somePrivilege', new MockAssertion(FALSE));
Assert::false( $acl->isAllowed('guest', NULL, 'somePrivilege') );
