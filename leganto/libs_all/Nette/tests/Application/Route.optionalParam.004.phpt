<?php

/**
 * Test: Route and auto-optional as optional sequences.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>/[<action>/[<id \d{1,3}>]]', array(
	'action' => 'default',
));

testRouteIn($route, '/presenter/action/12/any');

testRouteIn($route, '/presenter/action/12/', 'Presenter', array(
	'action' => 'action',
	'id' => '12',
	'test' => 'testvalue',
), '/presenter/action/12?test=testvalue');

testRouteIn($route, '/presenter/action/12', 'Presenter', array(
	'action' => 'action',
	'id' => '12',
	'test' => 'testvalue',
), '/presenter/action/12?test=testvalue');

testRouteIn($route, '/presenter/action/1234');

testRouteIn($route, '/presenter/action/', 'Presenter', array(
	'action' => 'action',
	'id' => NULL,
	'test' => 'testvalue',
), '/presenter/action/?test=testvalue');

testRouteIn($route, '/presenter/action', 'Presenter', array(
	'action' => 'action',
	'id' => NULL,
	'test' => 'testvalue',
), '/presenter/action/?test=testvalue');

testRouteIn($route, '/presenter/', 'Presenter', array(
	'action' => 'default',
	'id' => NULL,
	'test' => 'testvalue',
), '/presenter/?test=testvalue');

testRouteIn($route, '/presenter', 'Presenter', array(
	'action' => 'default',
	'id' => NULL,
	'test' => 'testvalue',
), '/presenter/?test=testvalue');

testRouteIn($route, '/');
