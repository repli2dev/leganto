<?php

/**
 * Test: Route with WithHost
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('//<host>.<domain>/<path>', array(
	'presenter' => 'Default',
	'action' => 'default',
));

testRouteIn($route, '/abc', 'Default', array(
	'host' => 'example',
	'domain' => 'com',
	'path' => 'abc',
	'action' => 'default',
	'test' => 'testvalue',
), '/abc?test=testvalue');
