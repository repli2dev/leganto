<?php

/**
 * Test: Route with WithUserClassAlt
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>/<id>', array(
	'id' => array(
		Route::PATTERN => '\d{1,3}',
	),
));

testRouteIn($route, '/presenter/12/', 'Presenter', array(
	'id' => '12',
	'test' => 'testvalue',
), '/presenter/12?test=testvalue');

testRouteIn($route, '/presenter/1234');

testRouteIn($route, '/presenter/');
