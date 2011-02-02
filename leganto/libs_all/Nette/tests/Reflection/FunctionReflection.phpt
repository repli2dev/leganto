<?php

/**
 * Test: FunctionReflection tests.
 *
 * @author     David Grudl
 * @package    Nette\Reflection
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



function bar() {}

$function = new FunctionReflection('bar');
Assert::null( $function->getExtension() );


$function = new FunctionReflection('sort');
Assert::equal( new ExtensionReflection('standard'), $function->getExtension() );
