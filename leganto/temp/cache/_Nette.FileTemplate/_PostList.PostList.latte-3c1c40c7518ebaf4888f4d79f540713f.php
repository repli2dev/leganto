<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.97315400 1313673685";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/PostList/PostList.latte";i:2;i:1313569328;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/PostList/PostList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'lt3mudy2n3')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="posts list">
<?php if ($user->isLoggedIn() && $enablePosting): ?>
	<div id="post-form-wrapper">
<?php $_ctrl = $control->getWidget("form"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	</div>
<?php endif ?>

<?php if (!empty($sorting)): ?>
    <table class="navigation">
        <tr>
            <th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Order by"), ENT_NOQUOTES) ?>:</th>
            <th><a href="<?php echo htmlSpecialChars($control->link("sortByTime!")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Sort by time')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Time"), ENT_NOQUOTES) ?></a></th>
        </tr>
    </table>
<?php endif ?>
    
<?php $zero = 0 ;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($posts) as $post): ?>
		<div class="clear"></div>
		<div class="item">
			<div class="info-side">
<?php if (isSet($achievements[$post->user])): ?>
					<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($achievements[$post->user]->posts, "posts")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate("level based on number of user's discussion posts")) ?>
" alt="<?php echo htmlSpecialChars($template->translate("level based on number of user's discussion posts")) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>/img/achievement_posts_vertical_small_<?php echo htmlSpecialChars($achievements[$post->user]->posts) ?>.png" />
<?php else: ?>
					<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($zero, "posts")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate("level based on number of user's discussion posts")) ?>
" alt="<?php echo htmlSpecialChars($template->translate("level based on number of user's discussion posts")) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>/img/achievement_posts_vertical_small_<?php echo htmlSpecialChars($zero) ?>.png" />
<?php endif ?>
				<img class="icon" src="<?php echo htmlSpecialChars($template->userico($post->user, 50)) ?>" />
				<span class="date"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($post->inserted), ENT_NOQUOTES) ?></span>
			</div>
			<div class="info-top">
				<a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($post->user))) ?>
" title="<?php echo htmlSpecialChars($post->userName) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($post->userName, 20), ENT_NOQUOTES) ?></a>
<?php if ($presenter->getUser()->isAllowed(Leganto\ACL\Resource::create($post), Leganto\ACL\Action::EDIT)): ?>
					<div class="control">
						<?php $confirm = $template->translate("Are you sure you want to delete a discussion post?"); $link    = $control->link('delete', $post->getId()) ?>
						<a onclick="return confirm('<?php echo $confirm ?>')" href="<?php echo $link ?>
" title="<?php echo htmlSpecialChars($template->translate('Delete')) ?>">
							<img src="<?php echo htmlSpecialChars($baseUri . '/img/ico/delete.png') ?>
" alt="<?php echo htmlSpecialChars($template->translate('Delete')) ?>" />
						</a>
					</div>
<?php endif ?>
			</div>
			<div class="content">
				<?php echo $template->texySafe($post->content) ?>

			</div>
			<div class="info-bottom">
<?php if ($enableLinks): ?>
					<a class="control" href="<?php echo htmlSpecialChars($presenter->link("Discussion:Posts", array($post->discussed,$post->discussionType))) ?>
" title="<?php echo htmlSpecialChars($template->translate("This link will only show the thread where the post was found.")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Link to discsussion"), ENT_NOQUOTES) ?></a>
<?php endif ?>
			</div>
			<div class="clear"></div>
		</div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

</div>

<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
