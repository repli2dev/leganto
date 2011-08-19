<?php //netteCache[01]000421a:2:{s:4:"time";s:21:"0.87727700 1313674356";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:99:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingBook/InsertingBook.latte";i:2;i:1312991222;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingBook/InsertingBook.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'yeb00wuyrp')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Insert book: check its existence'), ENT_NOQUOTES) ?></h1>
<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Firstly check whether the book has been already inserted by someone else. Type the book title into the field bellow.'), ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("searchForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('You can browse all books on our website on page'), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Search:allBooks")) ?>" title="<?php echo htmlSpecialChars($template->translate('All books')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('All books'), ENT_NOQUOTES) ?>
</a>.</p>