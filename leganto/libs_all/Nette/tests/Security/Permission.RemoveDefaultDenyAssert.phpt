<?php

/**
 * Test: Permission Ensures that removing the default deny rule results in assertion method being removed.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/MockAssertion.inc';



$acl = new Permission;
$acl->deny(NULL, NULL, NULL, new MockAssertion(FALSE));
Assert::true( $acl->isAllowed() );
$acl->removeDeny();
Assert::false( $acl->isAllowed() );
