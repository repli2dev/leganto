<?php

/**
 * Test: Permission Ensures that removal of a Role results in its rules being removed.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->addRole('guest');
$acl->allow('guest');
Assert::true( $acl->isAllowed('guest') );
$acl->removeRole('guest');
try {
	$acl->isAllowed('guest');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Role 'guest' does not exist.", $e );
}

$acl->addRole('guest');
Assert::false( $acl->isAllowed('guest') );
