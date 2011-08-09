<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.82843900 1312892890";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/UserList/UserList.latte";i:2;i:1312892889;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/UserList/UserList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'te1zcjc4d6')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>

<div class="users list">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($users) as $user): ?>
    <div class="item">
		<div class="info-side">
			<img class="icon" src="<?php echo htmlSpecialChars($template->userico($user->getId(), 50)) ?>" />
		</div>
		<div class="info-top">
			<a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($user->getId()))) ?>
" class="user-name" title="<?php echo htmlSpecialChars($user->nickname) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($user->nickname, 20), ENT_NOQUOTES) ?></a>
		</div>
		<div class="content">
			<?php echo $template->stripTags($template->texySafe(Nette\Utils\Strings::truncate($user->about, 100))) ?>

		</div>
		<div class="clear"></div>
    </div>
    <?php if ($iterator->getCounter() % 3 == 0): ?><div class="clear"></div><?php endif ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>
<div class="clear"></div>
<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
