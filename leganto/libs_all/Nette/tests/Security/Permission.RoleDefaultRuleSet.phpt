<?php

/**
 * Test: Permission Ensures that ACL-wide rules (all Resources and privileges) work properly for a particular Role.
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
$acl->deny('guest');
Assert::false( $acl->isAllowed('guest') );
