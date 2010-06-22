<?php

/**
 * Test: Nette\Templates\LatteFilter and macros test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Template.inc';



$template = new MockTemplate;
$template->registerFilter(new LatteFilter);
$template->render(NetteTestHelpers::getSection(__FILE__, 'template'));



__halt_compiler();

-----template-----
{block}
Block

------EXPECT------
Block
