<?php

/**
 * Test: Object extension method via interface.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Object.inc';



function IFirst_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



function ISecond_join(ISecond $that, $separator)
{
	return __METHOD__ . ' says ' . $that->foo . $separator . $that->bar;
}



ClassReflection::from('IFirst')->setExtensionMethod('join', 'IFirst_join');
ClassReflection::from('ISecond')->setExtensionMethod('join', 'ISecond_join');

$obj = new TestClass('Hello', 'World');
Assert::same( 'ISecond_join says Hello*World', $obj->join('*') );
