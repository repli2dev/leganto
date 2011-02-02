<?php

/**
 * Test: LatteFilter delimiters.
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
$template->setFile(dirname(__FILE__) . '/templates/latte.delimiters.phtml');
$template->registerFilter(new LatteFilter);
$template->registerHelperLoader('TemplateHelpers::loader');
$template->people = array('John', 'Mary', 'Paul');

Assert::match(file_get_contents(dirname(__FILE__) . '/LatteFilter.macros.009.expect'), (string) $template);
