<?php

/**
 * Test: LatteFilter and macros test.
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Template.inc';



$template = new MockTemplate;
$template->registerFilter(new LatteFilter);
Assert::match(<<<EOD

asdfgh
EOD

, $template->render(<<<EOD

{contentType text}
asdfgh
EOD
));
