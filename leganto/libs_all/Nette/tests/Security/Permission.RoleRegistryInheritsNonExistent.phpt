<?php

/**
 * Test: Permission Ensures that an exception is thrown when a non-existent Role is specified to each parameter of inherits().
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->addRole('guest');
try {
	$acl->roleInheritsFrom('nonexistent', 'guest');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Role 'nonexistent' does not exist.", $e );
}

try {
	$acl->roleInheritsFrom('guest', 'nonexistent');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Role 'nonexistent' does not exist.", $e );
}
