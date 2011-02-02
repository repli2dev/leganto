<?php

/**
 * Test: CliRouter invalid argument
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$_SERVER['argv'] = 1;
$httpRequest = new HttpRequest;

$router = new CliRouter;
Assert::null( $router->match($httpRequest) );
