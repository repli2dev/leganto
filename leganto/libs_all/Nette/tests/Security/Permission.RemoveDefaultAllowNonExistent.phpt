<?php

/**
 * Test: Permission Ensures that removing non-existent default allow rule does nothing.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->removeAllow();
Assert::false( $acl->isAllowed() );
