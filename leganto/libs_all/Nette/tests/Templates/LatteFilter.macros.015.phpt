<?php

/**
 * Test: LatteFilter and macros test.
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Template.inc';


$template = new MockTemplate;
$template->registerFilter(new LatteFilter);
try {
	$template->render('Block{/block}');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', 'Filter LatteFilter::__invoke: Tag {/block } was not expected here on line %a%.', $e );
}
