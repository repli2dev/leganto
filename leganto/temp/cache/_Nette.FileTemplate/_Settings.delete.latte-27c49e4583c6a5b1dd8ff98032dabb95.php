<?php //netteCache[01]000408a:2:{s:4:"time";s:21:"0.50362500 1313736668";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:86:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/delete.latte";i:2;i:1313516188;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/delete.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'umd0jxrgve')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbfab420b78d_subcontent')) { function _lbfab420b78d_subcontent($_l, $_args) { extract($_args)
?>
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Delete social network'), ENT_NOQUOTES) ?></h1>

	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Do you really want to delete this connection to a social network?'), ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("deleteForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	
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
