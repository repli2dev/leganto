<?php

/**
 * Test: Route with 'required' optional sequences II.
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';


$route = new Route('[<lang [a-z]{2}>[!-<sub>]/]<name>[/page-<page>]', array(
	'sub' => 'cz',
));

testRouteIn($route, '/cs-cz/name', 'querypresenter', array(
	'lang' => 'cs',
	'sub' => 'cz',
	'name' => 'name',
	'page' => NULL,
	'test' => 'testvalue',
), '/cs-cz/name?test=testvalue&presenter=querypresenter');

testRouteIn($route, '/cs-xx/name', 'querypresenter', array(
	'lang' => 'cs',
	'sub' => 'xx',
	'name' => 'name',
	'page' => NULL,
	'test' => 'testvalue',
), '/cs-xx/name?test=testvalue&presenter=querypresenter');

testRouteIn($route, '/name', 'querypresenter', array(
	'name' => 'name',
	'sub' => 'cz',
	'page' => NULL,
	'lang' => NULL,
	'test' => 'testvalue',
), '/name?test=testvalue&presenter=querypresenter');
