<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.46966000 1313754309";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Search/author.latte";i:2;i:1313754308;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Search/author.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'lqk84pvzyo')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb510d3da30e_subcontent')) { function _lb510d3da30e_subcontent($_l, $_args) { extract($_args)
?>
	<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Results"), ENT_NOQUOTES) ?></h3>
<?php if (isSet($message)): ?>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($message, ENT_NOQUOTES) ?></p>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Haven't you found the author? If you are sure that you didn't make any mistake in spelling please proceed and "), ENT_NOQUOTES) ?>
<a href="<?php echo htmlSpecialChars($presenter->link("Author:insert")) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("insert author"), ENT_NOQUOTES) ?></a>.</p>
<?php else: $_ctrl = $control->getWidget("authorList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ;
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
