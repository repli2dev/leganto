<?php

/**
 * Common code for Route test cases.
 */



function testRouteIn(Route $route, $uri, $expectedPresenter=NULL, $expectedParams=NULL, $expectedUri=NULL)
{
	// ==> $uri

	$uri = new UriScript("http://example.com$uri");
	$uri->scriptPath = '/';
	$uri->appendQuery(array(
		'test' => 'testvalue',
		'presenter' => 'querypresenter',
	));

	$httpRequest = new HttpRequest;
	$httpRequest->initialize();
	$httpRequest->setUri($uri);

	$request = $route->match($httpRequest);

	if ($request) { // matched
		$params = $request->getParams();
		//asort($params);
		Assert::same( $expectedPresenter, $request->getPresenterName() );
		Assert::same( $expectedParams, $params );

		unset($params['extra']);
		$request->setParams($params);
		$result = $route->constructUrl($request, $httpRequest);
		$result = strncmp($result, 'http://example.com', 18) ? $result : substr($result, 18);
		Assert::same( $expectedUri, $result );

	} else { // not matched
		// not matched
		Assert::null( $expectedPresenter );
	}
}



function testRouteOut(Route $route, $link, $params = array())
{
	$uri = new UriScript('http://example.com');
	$uri->scriptPath = '/';
	$httpRequest = new HttpRequest;
	$httpRequest->initialize();
	$httpRequest->setUri($uri);

	$request = new PresenterRequest($link, 'GET', $params);
	return $route->constructUrl($request, $httpRequest);
}

