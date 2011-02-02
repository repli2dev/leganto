<?php

/**
 * Test: LatteFilter and macros test.
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 * @phpini     short_open_tag=on
 */



require dirname(__FILE__) . '/../bootstrap.php';

require dirname(__FILE__) . '/Template.inc';


function xml($v) { echo $v; }

$template = new MockTemplate;
$template->registerFilter(new LatteFilter);

Assert::match(<<<EOD
<?xml version="1.0" ?>
12ok

EOD

, $template->render(<<<EOD
<?xml version="1.0" ?>
<?php xml(1) ?>
<? xml(2) ?>
<?php echo 'ok' ?>
EOD
));
