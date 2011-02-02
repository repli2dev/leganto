<?php

/**
 * Test: Route with WithNamedParamsInQuery
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('?action=<presenter> & act=<action [a-z]+>', array(
	'presenter' => 'Default',
	'action' => 'default',
));

testRouteIn($route, '/?act=action', 'Default', array(
	'action' => 'action',
	'test' => 'testvalue',
), '/?act=action&test=testvalue');

testRouteIn($route, '/?act=default', 'Default', array(
	'action' => 'default',
	'test' => 'testvalue',
), '/?test=testvalue');
