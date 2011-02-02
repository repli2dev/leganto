<?php

/**
 * Test: Permission Ensures that a privilege allowed for all Roles upon all Resources works properly.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->allow(NULL, NULL, 'somePrivilege');
Assert::true( $acl->isAllowed(NULL, NULL, 'somePrivilege') );
