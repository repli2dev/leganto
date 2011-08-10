<?php //netteCache[01]000417a:2:{s:4:"time";s:21:"0.90044700 1312986743";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:95:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/OpinionList/OpinionList.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/OpinionList/OpinionList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'yqrithce5o')
;//
// block _opinionlist
//
if (!function_exists($_l->blocks['_opinionlist'][] = '_lbc574966c2c__opinionlist')) { function _lbc574966c2c__opinionlist($_l, $_args) { extract($_args); $control->validateControl('opinionlist')
?>
<div class="opinions list<?php if ($showedInfo == 'user'): ?> opinions-user<?php endif ?>">

<?php if (!empty($sorting)): ?>
    <table class="navigation">
        <tr>
            <th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Order by"), ENT_NOQUOTES) ?>:</th>
            <th><a href="<?php echo htmlSpecialChars($control->link("sortByTime!")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Sort by time')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Time"), ENT_NOQUOTES) ?></a></th>
            <th><a href="<?php echo htmlSpecialChars($control->link("sortByScore!")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Sort by score')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Score"), ENT_NOQUOTES) ?></a></th>
        </tr>
    </table>
<?php endif ;$zero = 0 ;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($opinions) as $opinion): ?>
		<div class="item">
			
			<div class="info-side">
<?php if ($opinion->content != ""): if ($showedInfo == 'user'): if (isSet($achievements[$opinion->userId])): ?>
							<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($achievements[$opinion->userId]->books, "books")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate('level based on number of books read by the user')) ?>
" alt="<?php echo htmlSpecialChars($template->translate('level based on the number of books read by the user')) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>/img/achievement_books_vertical_small_<?php echo htmlSpecialChars($achievements[$opinion->userId]->books) ?>.png" />
<?php else: ?>
							<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($zero, "books")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate('level based on number of books read by the user')) ?>
" alt="<?php echo htmlSpecialChars($template->translate('level based on the number of books read by the user')) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>/img/achievement_books_vertical_small_<?php echo htmlSpecialChars($zero) ?>.png" />
<?php endif ?>
						<img class="icon" src="<?php echo htmlSpecialChars($template->userico($opinion->userId, 50)) ?>" />
<?php else: ?>
						<img class="icon" src="<?php echo htmlSpecialChars($template->bookcover($opinion->bookTitleId, 50)) ?>" />
<?php endif ;endif ;if ($showedInfo != 'user'): ?>
					<div class="clear"></div>
<?php endif ?>
				<span class="date"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($opinion->inserted), ENT_NOQUOTES) ?></span>
				
			</div>
			
			<div class="info-top">
				<img class="rating" src="<?php echo htmlSpecialChars($baseUri) ?>/img/rating_<?php echo htmlSpecialChars($opinion->rating) ?>
.png" alt="<?php echo htmlSpecialChars($template->rating($opinion->rating)) ?>" title="<?php echo htmlSpecialChars($template->rating($opinion->rating)) ?>" />
<?php if ($showedInfo == 'user'): ?>
					<a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($opinion->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($opinion->userName, 20), ENT_NOQUOTES) ?></a>
<?php else: ?>
					<a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($opinion->bookTitleId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->bookTitle, ENT_NOQUOTES) ?></a>
<?php endif ?>
			</div>

<?php if ($opinion->content != ""): ?>
				<div class="content">
					<?php echo $template->texySafe($opinion->content) ?>

				</div>
<?php endif ?>

<?php if ($opinion->content != ""): ?>
				<div class="info-bottom">
<?php if (!isset($showedOpinion) || $showedOpinion != $opinion->getId()): if (isset($discussions[$opinion->getId()])): ?>
							<a href="<?php echo htmlSpecialChars($control->link("showPosts!", array($opinion->getId()))) ?>
" class="control ajax"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Show'), ENT_NOQUOTES) ?>
 <?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussions[$opinion->getId()], ENT_NOQUOTES) ?>
 <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('comments'), ENT_NOQUOTES) ?></a>
<?php else: if ($presenter->getUser()->isLoggedIn()): ?>
								<a href="<?php echo htmlSpecialChars($control->link("showPosts!", array($opinion->getId()))) ?>
" class="control ajax"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Reply'), ENT_NOQUOTES) ?></a>
<?php endif ;endif ;endif ?>
				</div>
<?php endif ?>
			<div class="clear"></div>
		</div>
		<div class="opinion-posts">
<?php if (isset($showedOpinion) && $showedOpinion == $opinion->getId()): $_ctrl = $control->getWidget("postList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ?>
		</div>
		<div class="clear"></div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

<?php if (($showedPaginator)): $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;endif ?>
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
<div id="<?php echo $control->getSnippetId('opinionlist') ?>"><?php call_user_func(reset($_l->blocks['_opinionlist']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
