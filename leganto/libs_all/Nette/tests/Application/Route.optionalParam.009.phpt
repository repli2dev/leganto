<?php

/**
 * Test: Route with 'required' optional sequence.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';


$route = new Route('index[!.html]', array(
));

testRouteIn($route, '/index.html', 'querypresenter', array(
	'test' => 'testvalue',
), '/index.html?test=testvalue&presenter=querypresenter');

testRouteIn($route, '/index', 'querypresenter', array(
	'test' => 'testvalue',
), '/index.html?test=testvalue&presenter=querypresenter');
