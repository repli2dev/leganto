<?php

/**
 * Test: Route with optional sequence and two parameters.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';


$route = new Route('[<one [a-z]+><two [0-9]+>]', array(
	'one' => 'a',
	'two' => '1',
));

testRouteIn($route, '/a1', 'querypresenter', array(
	'one' => 'a',
	'two' => '1',
	'test' => 'testvalue',
), '/?test=testvalue&presenter=querypresenter');

testRouteIn($route, '/x1', 'querypresenter', array(
	'one' => 'x',
	'two' => '1',
	'test' => 'testvalue',
), '/x1?test=testvalue&presenter=querypresenter');

testRouteIn($route, '/a2', 'querypresenter', array(
	'one' => 'a',
	'two' => '2',
	'test' => 'testvalue',
), '/a2?test=testvalue&presenter=querypresenter');

testRouteIn($route, '/x2', 'querypresenter', array(
	'one' => 'x',
	'two' => '2',
	'test' => 'testvalue',
), '/x2?test=testvalue&presenter=querypresenter');
