<?php

/**
 * Test: Permission Ensures that removal of all Resources works.
 *
 * @author     David Grudl
 * @package    Nette\Security
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$acl = new Permission;
$acl->addResource('area');
$acl->removeAllResources();
Assert::false( $acl->hasResource('area') );
