<?php

/**
 * Test: ComponentContainer component factory 2.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



class TestClass extends ComponentContainer
{

	public function createComponent($name)
	{
		new self($this, $name);
	}

}


$a = new TestClass;
Assert::same( 'b', $a->getComponent('b')->name );
