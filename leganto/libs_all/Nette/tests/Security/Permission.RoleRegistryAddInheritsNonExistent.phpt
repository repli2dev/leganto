<?php

/**
 * Test: Permission Ensures that an exception is thrown when a non-existent Role is specified as a parent upon Role addition.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
try {
	$acl->addRole('guest', 'nonexistent');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Role 'nonexistent' does not exist.", $e );
}
