<?php

/**
 * Test: Route with UrlEncoding
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<param>', array(
	'presenter' => 'Presenter',
));

testRouteIn($route, '/a%3Ab', 'Presenter', array(
	'param' => 'a:b',
	'test' => 'testvalue',
), '/a%3Ab?test=testvalue');
