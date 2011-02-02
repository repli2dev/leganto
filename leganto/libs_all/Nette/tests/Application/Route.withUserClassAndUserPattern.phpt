<?php

/**
 * Test: Route with WithUserClassAndUserPattern
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



Route::addStyle('#numeric');
Route::setStyleProperty('#numeric', Route::PATTERN, '\d{1,3}');

$route = new Route('<presenter>/<id [\d.]+#numeric>', array());

testRouteIn($route, '/presenter/12.34/', 'Presenter', array(
	'id' => '12.34',
	'test' => 'testvalue',
), '/presenter/12.34?test=testvalue');

testRouteIn($route, '/presenter/123x');

testRouteIn($route, '/presenter/');
