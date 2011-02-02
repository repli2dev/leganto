<?php

/**
 * Test: Route with DashInParameter
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<para-meter>', array(
	'presenter' => 'Presenter',
));

testRouteIn($route, '/any', 'Presenter', array(
	'para-meter' => 'any',
	'test' => 'testvalue',
), '/any?test=testvalue');
