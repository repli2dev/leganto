<?php

/**
 * Test: TemplateFilters::relativeLinks()
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Template.inc';



$template = new MockTemplate;
$template->registerFilter(array('TemplateFilters', 'relativeLinks'));

$template->baseUri = 'http://example.com/~my/';

Assert::match(<<<EOD
<a href="http://example.com/~my/relative">link</a>

<a href="http://example.com/~my/relative#fragment">link</a>

<a href="#fragment">link</a>

<a href="http://url">link</a>

<a href="mailto:john@example.com">link</a>

<a href="/absolute-path">link</a>

<a href="//absolute">link</a>
EOD

, $template->render(<<<EOD
<a href="relative">link</a>

<a href="relative#fragment">link</a>

<a href="#fragment">link</a>

<a href="http://url">link</a>

<a href="mailto:john@example.com">link</a>

<a href="/absolute-path">link</a>

<a href="//absolute">link</a>
EOD
));
