<?php

/**
 * Test: Permission Ensures that an exception is thrown when a non-existent Resource is specified for removal.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
try {
	$acl->removeResource('nonexistent');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Resource 'nonexistent' does not exist.", $e );
}
