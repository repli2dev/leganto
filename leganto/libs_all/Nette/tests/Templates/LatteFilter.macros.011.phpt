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



$template = new Template;
$template->setFile(dirname(__FILE__) . '/templates/latte.xml.phtml');
$template->registerFilter(new LatteFilter);
$template->registerHelperLoader('TemplateHelpers::loader');

$template->hello = '<i>Hello</i>';
$template->id = ':/item';
$template->people = array('John', 'Mary', 'Paul', ']]>');
$template->comment = 'test -- comment';
$template->el = Html::el('div')->title('1/2"');

Assert::match(file_get_contents(dirname(__FILE__) . '/LatteFilter.macros.011.expect'), (string) $template);
