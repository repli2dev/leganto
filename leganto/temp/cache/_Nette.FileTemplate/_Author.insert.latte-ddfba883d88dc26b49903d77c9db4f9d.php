<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.57315600 1313754311";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Author/insert.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Author/insert.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'sj69b9s16q')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb29ec1b61c6_subcontent')) { function _lb29ec1b61c6_subcontent($_l, $_args) { extract($_args)
;if (isset($author)): ?>
		<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Edit author"), ENT_NOQUOTES) ?></h1>
<?php $_ctrl = $control->getWidget("insertingAuthor"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;else: ?>
		<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Insert a new author"), ENT_NOQUOTES) ?></h1>
<?php $_ctrl = $control->getWidget("insertingAuthor"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ;
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
