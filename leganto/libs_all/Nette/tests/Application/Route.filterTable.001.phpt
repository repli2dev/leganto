<?php

/**
 * Test: Route with FilterTable
 *
 * @author     David Grudl
 * @package    Nette\Application
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Route.inc';



Route::addStyle('#xlat', 'presenter');
Route::setStyleProperty('#xlat', Route::FILTER_TABLE, array(
	'produkt' => 'Product',
	'kategorie' => 'Category',
	'zakaznik' => 'Customer',
	'kosik' => 'Basket',
));

$route = new Route('<presenter #xlat>', array());

testRouteIn($route, '/kategorie/', 'Category', array(
	'test' => 'testvalue',
), '/kategorie?test=testvalue');

testRouteIn($route, '/other/', 'Other', array(
	'test' => 'testvalue',
), '/other?test=testvalue');
