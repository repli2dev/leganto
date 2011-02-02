<?php

/**
 * Test: Route with CamelcapsVsDash
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>', array(
	'presenter' => 'DefaultPresenter',
));

testRouteIn($route, '/abc-x-y-z', 'AbcXYZ', array(
	'test' => 'testvalue',
), '/abc-x-y-z?test=testvalue');

testRouteIn($route, '/', 'DefaultPresenter', array(
	'test' => 'testvalue',
), '/?test=testvalue');

testRouteIn($route, '/--');
