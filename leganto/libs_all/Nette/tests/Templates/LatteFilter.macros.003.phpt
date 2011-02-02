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



// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
TestHelpers::purge(TEMP_DIR);
Template::setCacheStorage(new MockCacheStorage(TEMP_DIR));



class MyHelper
{
	protected $count = 0;

	public function invoke($s)
	{
		$this->count++;
		return strtolower($s) . " ($this->count times)";
	}

}

function types()
{
	foreach (func_get_args() as $arg) $res[] = gettype($arg);
	return implode(', ', $res);
}



$template = new Template;
$template->setFile(dirname(__FILE__) . '/templates/latte.helpers.phtml');
$template->registerFilter(new LatteFilter);
$template->registerHelper('nl2br', 'nl2br');
$template->registerHelper('h1', array(new MyHelper, 'invoke'));
$template->registerHelper('h2', 'strtoupper');
$template->registerHelper('translate', 'strrev');
$template->registerHelper('types', 'types');
$template->registerHelperLoader('TemplateHelpers::loader');

$template->hello = 'Hello World';
$template->date = strtotime('2008-01-02');

Assert::match(file_get_contents(dirname(__FILE__) . '/LatteFilter.macros.003.expect'), (string) $template);
