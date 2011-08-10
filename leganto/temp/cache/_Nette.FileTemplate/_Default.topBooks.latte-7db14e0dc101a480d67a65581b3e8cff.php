<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.34016200 1312986685";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Default/topBooks.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Default/topBooks.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'rb1h0d7o2m')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbc581e7b6e1_subcontent')) { function _lbc581e7b6e1_subcontent($_l, $_args) { extract($_args)
?>
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Top books'), ENT_NOQUOTES) ?></h1>
<?php $_ctrl = $control->getWidget("topBookList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('You can browse all books on our website on page'), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Search:allBooks")) ?>" title="<?php echo htmlSpecialChars($template->translate('All books')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('All books'), ENT_NOQUOTES) ?></a>.</p>
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
