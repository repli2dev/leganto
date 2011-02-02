<?php

/**
 * Test: SimpleRouter with secured connection.
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
), SimpleRouter::SECURED);

$httpRequest = new MockHttpRequest;
$httpRequest->setQuery(array(
	'presenter' => 'myPresenter',
));

$req = new PresenterRequest(
	'othermodule:presenter',
	HttpRequest::GET,
	array()
);

$url = $router->constructUrl($req, $httpRequest);
Assert::same( 'https://nette.org/file.php?presenter=othermodule%3Apresenter',  $url );
