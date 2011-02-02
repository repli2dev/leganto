<?php

/**
 * Test: Permission Ensures that a privilege denied for a particular Role upon all Resources works properly.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->addRole('guest');
$acl->allow('guest');
$acl->deny('guest', NULL, 'somePrivilege');
Assert::false( $acl->isAllowed('guest', NULL, 'somePrivilege') );
