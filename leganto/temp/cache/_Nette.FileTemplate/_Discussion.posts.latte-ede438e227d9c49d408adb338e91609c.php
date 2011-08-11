<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.20629000 1313057829";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Discussion/posts.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Discussion/posts.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '1tmzldkib3')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbec93d5281f_subcontent')) { function _lbec93d5281f_subcontent($_l, $_args) { extract($_args)
?>
<h1>
    <?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->name, ENT_NOQUOTES) ?>

<?php if ($discussion->subname != null): ?>
	(<?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->subname, ENT_NOQUOTES) ?>)
<?php endif ?>
</h1>
<p>
<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Created'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->time($discussion->inserted), ENT_NOQUOTES) ?>

<br />
<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Last post inserted'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->time($discussion->lastPostInserted), ENT_NOQUOTES) ?>

<br />
<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Number of posts'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->numberOfPosts, ENT_NOQUOTES) ?>

</p>
<?php $_ctrl = $control->getWidget("postList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
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
