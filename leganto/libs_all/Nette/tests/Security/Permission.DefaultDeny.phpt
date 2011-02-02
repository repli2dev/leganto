<?php

/**
 * Test: Permission Ensures that by default denies access to everything by all.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
Assert::false( $acl->isAllowed() );
Assert::false( $acl->isAllowed(NULL, NULL, 'somePrivilege') );

$acl->addRole('guest');
Assert::false( $acl->isAllowed('guest') );
Assert::false( $acl->isAllowed('guest', NULL, 'somePrivilege') );
