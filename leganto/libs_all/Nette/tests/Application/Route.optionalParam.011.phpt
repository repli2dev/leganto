<?php

/**
 * Test: Route with optional sequence precedence.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';


$route = new Route('[<one>/][<two>]', array(
));

testRouteIn($route, '/one', 'querypresenter', array(
	'one' => 'one',
	'two' => NULL,
	'test' => 'testvalue',
), '/one/?test=testvalue&presenter=querypresenter');

$route = new Route('[<one>/]<two>', array(
	'two' => NULL,
));

testRouteIn($route, '/one', 'querypresenter', array(
	'one' => 'one',
	'two' => NULL,
	'test' => 'testvalue',
), '/one/?test=testvalue&presenter=querypresenter');
