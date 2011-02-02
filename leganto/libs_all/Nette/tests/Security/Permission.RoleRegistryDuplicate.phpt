<?php

/**
 * Test: Permission Ensures that the same Role cannot be registered more than once to the registry.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
try {
	$acl->addRole('guest');
	$acl->addRole('guest');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Role 'guest' already exists in the list.", $e );
}
