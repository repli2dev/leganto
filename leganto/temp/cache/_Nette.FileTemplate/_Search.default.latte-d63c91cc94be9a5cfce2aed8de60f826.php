<?php //netteCache[01]000407a:2:{s:4:"time";s:21:"0.05112100 1313754577";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:85:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Search/default.latte";i:2;i:1313754575;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Search/default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'sqzppbdiw9')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb40fd3f8f76_subcontent')) { function _lb40fd3f8f76_subcontent($_l, $_args) { extract($_args)
?>
	<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Results'), ENT_NOQUOTES) ?></h3>
<?php if (isSet($message)): ?>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($message, ENT_NOQUOTES) ?></p>
<?php else: $_ctrl = $control->getWidget("searchList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ?>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Haven't you found the book? If you are sure that you didn't make any mistake in spelling please proceed and "), ENT_NOQUOTES) ?>
<a href="<?php echo htmlSpecialChars($presenter->link("Book:insert", array(true))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("insert new book"), ENT_NOQUOTES) ?></a>.</p>
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
