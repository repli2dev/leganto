<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.63162500 1312986462";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Book/opinions.latte";i:2;i:1312986408;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Book/opinions.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '8soytzgcvt')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb2c0830e4eb_subcontent')) { function _lb2c0830e4eb_subcontent($_l, $_args) { extract($_args)
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Opinions on the book"), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title . (($book->subtitle == NULL) ? "" : ": " . $book->subtitle), ENT_NOQUOTES) ?></h1>

<div id="opinions">
<?php $_ctrl = $control->getWidget("opinionList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;if ($opinionCount == 0): ?>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("There are no text opinions, be the one who write the first one!"), ENT_NOQUOTES) ?></p>
<?php endif ?>
</div>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = true; unset($_extends, $template->_extends);


if ($_l->extends) {
	ob_start();
} elseif (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
$_l->extends = "layout.latte" ?>

<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
