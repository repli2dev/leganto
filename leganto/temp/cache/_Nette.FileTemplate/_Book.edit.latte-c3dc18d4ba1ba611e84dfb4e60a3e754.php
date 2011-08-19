<?php //netteCache[01]000402a:2:{s:4:"time";s:21:"0.91145000 1313753636";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:80:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Book/edit.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Book/edit.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '6h4f3dnoqu')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbc9816c6f0b_subcontent')) { function _lbc9816c6f0b_subcontent($_l, $_args) { extract($_args)
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Editing book"), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title . (($book->subtitle == NULL) ? "" : ": " . $book->subtitle), ENT_NOQUOTES) ?></h1>
<?php $_ctrl = $control->getWidget("insertingBook"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
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
