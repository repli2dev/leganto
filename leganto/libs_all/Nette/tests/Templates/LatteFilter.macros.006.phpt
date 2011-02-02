<?php

/**
 * Test: LatteFilter and macros test.
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 * @keepTrailingSpaces
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Template.inc';



class MockControl extends Control
{

	public function getSnippetId($name = NULL)
	{
		return 'sni__' . $name;
	}

}



$template = new MockTemplate;
$template->registerFilter(new LatteFilter);
$template->control = new MockControl;
$template->render(file_get_contents(dirname(__FILE__) . '/templates/latte.snippet.phtml'));

Assert::match(file_get_contents(dirname(__FILE__) . '/LatteFilter.macros.006.expect'), $template->compiled);
