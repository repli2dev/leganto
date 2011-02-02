<?php

/**
 * Test: SimpleRouter and modules.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/SimpleRouter.inc';



$router = new SimpleRouter(array(
	'module' => 'main:sub',
));

$httpRequest = new MockHttpRequest;
$httpRequest->setQuery(array(
	'presenter' => 'myPresenter',
));

$req = $router->match($httpRequest);
Assert::same( 'main:sub:myPresenter',  $req->getPresenterName() );

$url = $router->constructUrl($req, $httpRequest);
Assert::same( 'http://nette.org/file.php?presenter=myPresenter',  $url );

$req = new PresenterRequest(
	'othermodule:presenter',
	HttpRequest::GET,
	array()
);
$url = $router->constructUrl($req, $httpRequest);
Assert::null( $url );
