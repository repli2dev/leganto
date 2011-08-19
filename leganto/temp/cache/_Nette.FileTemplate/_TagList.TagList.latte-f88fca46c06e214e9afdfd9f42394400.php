<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.23351400 1313673942";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/TagList/TagList.latte";i:2;i:1313673940;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/TagList/TagList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'bkbdisk7io')
;//
// block _tagList
//
if (!function_exists($_l->blocks['_tagList'][] = '_lbaa92dd17ff__tagList')) { function _lbaa92dd17ff__tagList($_l, $_args) { extract($_args); $control->validateControl('tagList')
?>
<div id="tags">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($tags) as $tag): if ($tag->name != NULL): ?>
			<a href="<?php echo htmlSpecialChars($presenter->link("Search:default", array($tag->name))) ?>
" class="tag"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($tag->name, ENT_NOQUOTES) ?></a>
<?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

<?php if ($user->isAllowed(Leganto\ACL\Resource::TAG, Leganto\ACL\Action::INSERT)): $_ctrl = $control->getWidget("form"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
		<div class="clear"></div>
<?php endif ?>
</div>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extends) ? FALSE : $template->_extends; unset($_extends, $template->_extends);


if ($_l->extends) {
	ob_start();
} elseif (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="<?php echo $control->getSnippetId('tagList') ?>"><?php call_user_func(reset($_l->blocks['_tagList']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
