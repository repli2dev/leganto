<?php //netteCache[01]000403a:2:{s:4:"time";s:21:"0.07347700 1313673202";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:81:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/Feed.latte";i:2;i:1313584209;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/Feed.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '5gisqprp2l')
;//
// block _feed
//
if (!function_exists($_l->blocks['_feed'][] = '_lb92d60ad2b7__feed')) { function _lb92d60ad2b7__feed($_l, $_args) { extract($_args); $control->validateControl('feed')
?>
<div id="feed" class="list">
    <table class="navigation">
        <tr>
<?php if (!empty($allSwitcher)): ?>
				<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("All"), ENT_NOQUOTES) ?></th>
				<th><a class="ajax" href="<?php echo htmlSpecialChars($control->link("followed")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Followed"), ENT_NOQUOTES) ?></a></th>
<?php else: ?>
				<th><a class="ajax" href="<?php echo htmlSpecialChars($control->link("all")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("All"), ENT_NOQUOTES) ?></a></th>
				<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Followed'), ENT_NOQUOTES) ?></th>
<?php endif ?>
        </tr>
    </table>

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($feed) as $item): $content = explode("#$#",$item->content) ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_NEW_OPINION): Nette\Latte\Macros\CoreMacros::includeTemplate("newOpinion.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_UPDATED_OPINION): Nette\Latte\Macros\CoreMacros::includeTemplate("updatedOpinion.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_SHELVED): Nette\Latte\Macros\CoreMacros::includeTemplate("shelved.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_DESHELVED): Nette\Latte\Macros\CoreMacros::includeTemplate("deshelved.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_NEW_FOLLOWER): Nette\Latte\Macros\CoreMacros::includeTemplate("newFollower.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_NEW_BOOK): Nette\Latte\Macros\CoreMacros::includeTemplate("newBook.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_NEW_DISCUSSION): Nette\Latte\Macros\CoreMacros::includeTemplate("newDiscussion.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_NEW_POST): Nette\Latte\Macros\CoreMacros::includeTemplate("newPost.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;if ($item->type == \Leganto\DB\Feed\Entity::TYPE_NEW_USER): Nette\Latte\Macros\CoreMacros::includeTemplate("newUser.latte", array('content' => $content, 'item' => $item) + $template->getParams(), $_l->templates['5gisqprp2l'])->render() ;endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
<hr class="cleaner" />
<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render(array('ajax' => TRUE)) ?>
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
$_ctrl = $control->getWidget("flashMessages"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
<div id="<?php echo $control->getSnippetId('feed') ?>"><?php call_user_func(reset($_l->blocks['_feed']), $_l, $template->getParams()) ?>
</div><div id="main-info">
	
	<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Did you know, that ...?'), ENT_NOQUOTES) ?></h3>
	<div class="hint">
		<p><?php echo $hint->text ?></p>
		<div class="clear"></div>
	</div>

<?php if (isSet($recommend) && $recommend != false): ?>
		<h3 style="text-align: center"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('We recommend'), ENT_NOQUOTES) ?></h3>
<?php $_ctrl = $control->getWidget("bookList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ?>

<?php $_ctrl = $control->getWidget("twitter"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>

	<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Others"), ENT_NOQUOTES) ?></h3>
	<div class="rss">
		<p><a href="<?php echo htmlSpecialChars($presenter->link("Rss:", array($user->getId()))) ?>
"><img src="/img/socialnetworks/rss.png" alt="<?php echo htmlSpecialChars($template->translate("This page in form of RSS")) ?>
" /></a><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Watch your feed via RSS reader.'), ENT_NOQUOTES) ?></p>
		<div class="clear"></div>
	</div>
</div>
<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
