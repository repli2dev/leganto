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

$template->render(<<<EOD
{* k�d  *}

@{if TRUE}
		{* k�d  *}
@{else}
		{* k�d  *}
@{/if}

{* k�d  *}

EOD
);

Assert::match('<?php
%A%

if (%ns%SnippetHelper::$outputAllowed) {
} if (TRUE): if (%ns%SnippetHelper::$outputAllowed) { ?>
		<?php } ;else: if (%ns%SnippetHelper::$outputAllowed) { ?>
		<?php } endif ;if (%ns%SnippetHelper::$outputAllowed) { ?>

<?php
}

', $template->compiled);
