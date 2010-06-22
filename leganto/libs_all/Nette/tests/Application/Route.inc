<?php

/**
 * Common code for Route test cases.
 */



function testRouteIn(Route $route, $uri)
{
	output("==> $uri");

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
		dump( $request->getPresenterName() );
		dump( $params );

		unset($params['extra']);
		$request->setParams($params);
		$result = $route->constructUrl($request, $httpRequest);
		$result = strncmp($result, 'http://example.com', 18) ? $result : substr($result, 18);
		dump( $result );

	} else { // not matched
		output('not matched');
	}
}



function testRouteOut(Route $route, $link, $params = array())
{
	output("==> [$link]");

	$uri = new UriScript("http://example.com");
	$uri->scriptPath = '/';
	$httpRequest = new HttpRequest;
	$httpRequest->initialize();
	$httpRequest->setUri($uri);

	$request = new PresenterRequest($link, 'GET', $params);
	$result = $route->constructUrl($request, $httpRequest);

	dump( $result );
}

