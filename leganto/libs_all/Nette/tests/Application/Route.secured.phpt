<?php

/**
 * Test: Route with Secured
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<param>', array(
	'presenter' => 'Presenter',
), Route::SECURED);

testRouteIn($route, '/any', 'Presenter', array(
	'param' => 'any',
	'test' => 'testvalue',
), 'https://example.com/any?test=testvalue');
