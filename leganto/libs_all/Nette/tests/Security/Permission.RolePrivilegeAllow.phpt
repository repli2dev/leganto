<?php

/**
 * Test: Permission Ensures that a privilege allowed for a particular Role upon all Resources works properly.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->addRole('guest');
$acl->allow('guest', NULL, 'somePrivilege');
Assert::true( $acl->isAllowed('guest', NULL, 'somePrivilege') );
