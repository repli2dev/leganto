<?php //netteCache[01]000405a:2:{s:4:"time";s:21:"0.42098100 1313753755";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:83:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Book/default.latte";i:2;i:1313753736;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Book/default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '6tdtcvug8k')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbd1c8289d9e_subcontent')) { function _lbd1c8289d9e_subcontent($_l, $_args) { extract($_args)
;$_ctrl = $control->getWidget("bookView"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	<h2 title="<?php echo htmlSpecialChars($template->translate("Short keywords describing the book.")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Tags'), ENT_NOQUOTES) ?></h2>
<?php $_ctrl = $control->getWidget("tagList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;if ($user->isLoggedIn()): ?>
		<h2 title="<?php echo htmlSpecialChars($template->translate("User you follow and has something in common with this book")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Followed users"), ENT_NOQUOTES) ?></h2>
<?php $_ctrl = $control->getWidget("followedUser"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ?>
	<h2 title="<?php echo htmlSpecialChars($template->translate("Opinions written by other users, we tried to serve newest opinions.")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Opinions'), ENT_NOQUOTES) ?></h2>
<?php if ($opinionCount == 0): ?>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("There are no text opinions."), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:addOpinion", array($book->getId()))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Be first!"), ENT_NOQUOTES) ?></a></p>
<?php else: ?>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('These opinions we choosed for faster overview.'), ENT_NOQUOTES) ?><br />
		<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("There can be more opinions (even non text) than you see. Please visit"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:opinions", array($bookId))) ?>
" title="<?php echo htmlSpecialChars($template->translate("Opinions on the book")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Opinions on the book"), ENT_NOQUOTES) ?></a>.</p>
<?php endif ;$_ctrl = $control->getWidget("opinionList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	<h2 title="<?php echo htmlSpecialChars($template->translate("This book in other languages.")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Related books'), ENT_NOQUOTES) ?></h2>
<?php $_ctrl = $control->getWidget("relatedBookList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	<h2 title="<?php echo htmlSpecialChars($template->translate("Editions of this book with ISBNs and book covers.")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Editions'), ENT_NOQUOTES) ?></h2>
<?php $_ctrl = $control->getWidget("editionList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
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
