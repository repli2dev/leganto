<?php //netteCache[01]000408a:2:{s:4:"time";s:21:"0.60858500 1312903819";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:86:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Search/allBooks.latte";i:2;i:1312889007;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Search/allBooks.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'su6ub2phkz')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb451bf948da_content')) { function _lb451bf948da_content($_l, $_args) { extract($_args)
?>
<div id="content">
<?php $_ctrl = $control->getWidget("flashMessages"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('All books'), ENT_NOQUOTES) ?></h2>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('All books sorted from the newest to the oldest.'), ENT_NOQUOTES) ?></p>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Number of all books'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($numOfAllBooks, ENT_NOQUOTES) ?><br />
	<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Number of all authors'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($numOfAllAuthors, ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("searchList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
</div><?php
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
