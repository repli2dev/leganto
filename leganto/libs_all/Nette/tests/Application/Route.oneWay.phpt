<?php

/**
 * Test: Route with OneWay
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
), Route::ONE_WAY);

testRouteIn($route, '/presenter/action/', 'Presenter', array(
	'action' => 'action',
	'test' => 'testvalue',
), NULL);
