<?php //netteCache[01]000404a:2:{s:4:"time";s:21:"0.75008000 1313673206";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:82:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/User/layout.latte";i:2;i:1313512431;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/User/layout.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'su0jo1a49r')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbdf3e500627_content')) { function _lbdf3e500627_content($_l, $_args) { extract($_args)
?>
    <div id="right">
<?php $_ctrl = $control->getWidget("submenu"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;$_ctrl = $control->getWidget("userIcon"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
    </div>
    <div id="left">
		<div id="content">
<?php $_ctrl = $control->getWidget("flashMessages"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;call_user_func(reset($_l->blocks['subcontent']), $_l, get_defined_vars())  ?>
		</div>
    </div>
<?php
}}

//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lba340e6b276_subcontent')) { function _lba340e6b276_subcontent($_l, $_args) { extract($_args)
;
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
$_l->extends = "../@layout.latte" ?>

<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
