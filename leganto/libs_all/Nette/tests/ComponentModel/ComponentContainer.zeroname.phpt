<?php

/**
 * Test: ComponentContainer and '0' name.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$container = new ComponentContainer;
$container->addComponent(new ComponentContainer, 0);
Assert::same( '0', $container->getComponent(0)->getName() );
