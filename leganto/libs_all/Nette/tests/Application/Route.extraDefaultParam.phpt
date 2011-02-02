<?php

/**
 * Test: Route with ExtraDefaultParam
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>/<action>/<id \d{1,3}>/', array(
	'extra' => NULL,
));

testRouteIn($route, '/presenter/action/12/any');

testRouteIn($route, '/presenter/action/12', 'Presenter', array(
	'action' => 'action',
	'id' => '12',
	'extra' => NULL,
	'test' => 'testvalue',
), '/presenter/action/12/?test=testvalue');

testRouteIn($route, '/presenter/action/1234');

testRouteIn($route, '/presenter/action/');

testRouteIn($route, '/presenter');

testRouteIn($route, '/');
