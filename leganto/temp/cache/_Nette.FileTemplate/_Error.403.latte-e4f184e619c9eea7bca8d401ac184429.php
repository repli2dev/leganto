<?php //netteCache[01]000402a:2:{s:4:"time";s:21:"0.77003500 1313056352";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:80:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Error/403.latte";i:2;i:1313055190;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Error/403.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ckw0ejfdy4')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb4835bc0355_subcontent')) { function _lb4835bc0355_subcontent($_l, $_args) { extract($_args)
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('403 Unauthorized'), ENT_NOQUOTES) ?></h1>
<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Sorry, you were trying to reach a page which can be accessed only by users who are logged in and authorized. Please log in or sign in on our'), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Default:")) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Main page'), ENT_NOQUOTES) ?></a>.</p>
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
