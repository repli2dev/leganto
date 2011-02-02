<?php

/**
 * Test: TemplateFilters::texyElements()
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Template.inc';



class MockTexy
{
	function process($text, $singleLine = FALSE)
	{
		return '<...>';
	}
}


TemplateFilters::$texy = new MockTexy;

$template = new MockTemplate;
$template->registerFilter(array('TemplateFilters', 'texyElements'));

Assert::match(<<<EOD
<...>


<...>


<...>
EOD

, $template->render(<<<EOD
<texy>**Hello World**</texy>


<texy>
Multi line
----------

example
</texy>


<texy param="value">
Second multi line
-----------------

example
</texy>
EOD
));
