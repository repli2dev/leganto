<?php

/**
 * Test: PropertyReflection tests.
 *
 * @author     David Grudl
 * @package    Nette\Reflection
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



class A
{
	public $prop;
}

class B extends A
{
}

$propInfo = new PropertyReflection('B', 'prop');
Assert::equal( new ClassReflection('A'), $propInfo->getDeclaringClass() );
