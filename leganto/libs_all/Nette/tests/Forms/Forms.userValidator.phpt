<?php

/**
 * Test: Forms user validator.
 *
 * @author     David Grudl
 * @package    Nette\Forms
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



function myValidator1($item, $arg)
{
	return $item->getValue() != $arg;
}


$form = new Form();
$form->addText('name', 'Text:', 10)
	->addRule('myValidator1', 'Value %d is not allowed!', 11)
	->addRule(~'myValidator1', 'Value %d is required!', 22);

// TODO: add assert