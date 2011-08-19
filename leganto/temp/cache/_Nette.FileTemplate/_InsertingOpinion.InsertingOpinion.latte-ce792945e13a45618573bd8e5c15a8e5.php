<?php //netteCache[01]000428a:2:{s:4:"time";s:21:"0.55609400 1313673252";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:105:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingOpinion/InsertingOpinion.latte";i:2;i:1312991222;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/InsertingOpinion/InsertingOpinion.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'iiyhbu8afh')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Your opinion on'), ENT_NOQUOTES) ?>
 <?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></h1>
<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Please write your opinion for other human readers, they would surely know why they should read the book. However do not make spoilers! Select appropriate number of stars - book you normally enjoy should have tree stars.'), ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("addOpinionForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
<p>You can use texy for formatting, emoticons or links to other books. <a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array(64))) ?>
">See help</a>.</p>