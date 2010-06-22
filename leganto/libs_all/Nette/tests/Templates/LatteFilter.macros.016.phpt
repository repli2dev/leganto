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
echo $template->compiled;



__halt_compiler();

-----template-----
{* kód  *}

@{if TRUE}
		{* kód  *}
@{else}
		{* kód  *}
@{/if}

{* kód  *}

------EXPECT------

<?php
%A%

if (SnippetHelper::$outputAllowed) {
} if (TRUE): if (SnippetHelper::$outputAllowed) { ?>
		<?php } ;else: if (SnippetHelper::$outputAllowed) { ?>
		<?php } endif ;if (SnippetHelper::$outputAllowed) { ?>

<?php
}
