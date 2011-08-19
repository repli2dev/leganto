<?php //netteCache[01]000426a:2:{s:4:"time";s:21:"0.11747900 1313753335";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:103:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingBook/InsertingBookForm.latte";i:2;i:1312991222;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingBook/InsertingBookForm.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'y2phn9ndru')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
if (!$control->isEditing()): ?>
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Insert book: fill in info about it'), ENT_NOQUOTES) ?></h1>
<?php endif ?>
<div id="insert-book-wrapper">
<?php $_ctrl = $control->getWidget("bookForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
</div>