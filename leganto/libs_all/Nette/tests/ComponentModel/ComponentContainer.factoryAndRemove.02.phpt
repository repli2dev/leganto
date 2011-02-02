<?php

/**
 * Test: ComponentContainer component factory & remove inside.
 *
 * @author     David Grudl
 * @package    Nette
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



class TestClass extends ComponentContainer
{

	public function createComponentB($name)
	{
		$b = new self($this, $name);
		$this->removeComponent($b);
		return new self;
	}

}


$a = new TestClass;
Assert::same( 'b', $a->getComponent('b')->name );
