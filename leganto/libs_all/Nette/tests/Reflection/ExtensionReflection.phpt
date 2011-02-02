<?php

/**
 * Test: ExtensionReflection tests.
 *
 * @author     David Grudl
 * @package    Nette\Reflection
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$ext = new ExtensionReflection('standard');
$funcs = $ext->getFunctions();
Assert::equal( new FunctionReflection('sleep'), $funcs['sleep'] );



$ext = new ExtensionReflection('reflection');
Assert::equal( array(
	'ReflectionException' => new ClassReflection('ReflectionException'),
	'Reflection' => new ClassReflection('Reflection'),
	'Reflector' => new ClassReflection('Reflector'),
	'ReflectionFunctionAbstract' => new ClassReflection('ReflectionFunctionAbstract'),
	'ReflectionFunction' => new ClassReflection('ReflectionFunction'),
	'ReflectionParameter' => new ClassReflection('ReflectionParameter'),
	'ReflectionMethod' => new ClassReflection('ReflectionMethod'),
	'ReflectionClass' => new ClassReflection('ReflectionClass'),
	'ReflectionObject' => new ClassReflection('ReflectionObject'),
	'ReflectionProperty' => new ClassReflection('ReflectionProperty'),
	'ReflectionExtension' => new ClassReflection('ReflectionExtension'),
), $ext->getClasses() );
