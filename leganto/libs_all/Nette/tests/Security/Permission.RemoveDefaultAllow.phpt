<?php

/**
 * Test: Permission Ensures that removing the default allow rule results in default deny rule being assigned.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->allow();
Assert::true( $acl->isAllowed() );
$acl->removeAllow();
Assert::false( $acl->isAllowed() );
