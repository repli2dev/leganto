<?php

/**
 * Test: Annotations using user classes.
 *
 * @author     David Grudl
 * @package    Nette\Reflection
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



class SecuredAnnotation extends Annotation
{
	public $role;
	public $level;
	public $value;
}


/**
 * @secured(disabled)
 */
class TestClass {

	/** @secured(role = "admin", level = 2) */
	public $foo;

}



// Class annotations

$rc = new ClassReflection('TestClass');
Assert::equal( array(
	'secured' => array(
		new SecuredAnnotation(array(
			'role' => NULL,
			'level' => NULL,
			'value' => 'disabled',
		)),
	),
), $rc->getAnnotations() );


Assert::equal( array(
	'secured' => array(
		new SecuredAnnotation(array(
			'role' => 'admin',
			'level' => 2,
			'value' => NULL,
		)),
	),
), $rc->getProperty('foo')->getAnnotations() );
