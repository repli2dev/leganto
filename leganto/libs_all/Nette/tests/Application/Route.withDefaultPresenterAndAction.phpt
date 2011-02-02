<?php

/**
 * Test: Route with WithDefaultPresenterAndAction
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>/<action>', array(
	'presenter' => 'Default',
	'action' => 'default',
));

testRouteIn($route, '/presenter/action/', 'Presenter', array(
	'action' => 'action',
	'test' => 'testvalue',
), '/presenter/action?test=testvalue');

testRouteIn($route, '/default/default/', 'Default', array(
	'action' => 'default',
	'test' => 'testvalue',
), '/?test=testvalue');

testRouteIn($route, '/presenter', 'Presenter', array(
	'action' => 'default',
	'test' => 'testvalue',
), '/presenter/?test=testvalue');

testRouteIn($route, '/', 'Default', array(
	'action' => 'default',
	'test' => 'testvalue',
), '/?test=testvalue');
