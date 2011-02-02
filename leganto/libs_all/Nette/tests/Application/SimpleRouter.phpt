<?php

/**
 * Test: SimpleRouter basic functions.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/SimpleRouter.inc';



$router = new SimpleRouter(array(
	'id' => 12,
	'any' => 'anyvalue',
));

$httpRequest = new MockHttpRequest;
$httpRequest->setQuery(array(
	'presenter' => 'myPresenter',
	'action' => 'action',
	'id' => '12',
	'test' => 'testvalue',
));

$req = $router->match($httpRequest);
Assert::same( 'myPresenter',  $req->getPresenterName() );
Assert::same( 'action',  $req->params['action'] );
Assert::same( '12',  $req->params['id'] );
Assert::same( 'testvalue',  $req->params['test'] );
Assert::same( 'anyvalue',  $req->params['any'] );

$url = $router->constructUrl($req, $httpRequest);
Assert::same( 'http://nette.org/file.php?action=action&test=testvalue&presenter=myPresenter',  $url );
