<?php //netteCache[01]000428a:2:{s:4:"time";s:21:"0.38943000 1313753333";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:105:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingBook/InsertingBookResult.latte";i:2;i:1312991222;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingBook/InsertingBookResult.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'xlmgkgkdr3')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Insert book: look if it is mentioned"), ENT_NOQUOTES) ?></h1>

<div id="mentioned-yes">
	<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Yes"), ENT_NOQUOTES) ?></h2>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("The book already exists on our web. You don't have to insert it again. If you want to add your opinion do it from the book detail."), ENT_NOQUOTES) ?></p>
</div>
<div id="mentioned-no">
	<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('No'), ENT_NOQUOTES) ?></h2>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Please continue to the next page where you will be asked for some other information."), ENT_NOQUOTES) ?></p>
	<p><strong><a class="button" href="<?php echo htmlSpecialChars($control->link("continueToInsert", array($title))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Continue"), ENT_NOQUOTES) ?></a></strong></p>
</div>
<div class="clear"></div>
<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Found books"), ENT_NOQUOTES) ?></h2>
<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Did it see the same book you have just read? If you are not sure open the book and ensure that the title, subtitle and authors are the same (ignore book cover)."), ENT_NOQUOTES) ?>

<?php $_ctrl = $control->getWidget("searchForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;$_ctrl = $control->getWidget("bookList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
