<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.93190600 1313673212";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/default.latte";i:2;i:1313516063;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 's01f8gdy0b')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb235c90a10c_subcontent')) { function _lb235c90a10c_subcontent($_l, $_args) { extract($_args)
?>
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Settings"), ENT_NOQUOTES) ?></h1>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("On this page you can change your profile information and password. Fill password only when you intend to change it or you are changing your e-mail address. All other information is alterable without entering the password."), ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("settingsForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
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
