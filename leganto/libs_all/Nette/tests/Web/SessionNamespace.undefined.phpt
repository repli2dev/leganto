<?php

/**
 * Test: SessionNamespace undefined property.
 *
 * @author     David Grudl
 * @package    Nette\Web
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$session = new Session;
$namespace = $session->getNamespace('one');
Assert::false( isset($namespace->undefined) );
Assert::null( $namespace->undefined, 'Getting value of non-existent key' );
Assert::same( '', http_build_query($namespace->getIterator()) );
