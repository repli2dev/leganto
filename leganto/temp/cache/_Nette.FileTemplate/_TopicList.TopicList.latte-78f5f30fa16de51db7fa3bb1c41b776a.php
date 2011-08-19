<?php //netteCache[01]000413a:2:{s:4:"time";s:21:"0.97191300 1313674321";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:91:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/TopicList/TopicList.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/TopicList/TopicList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'srzxafauln')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="topics list">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($topics) as $topic): ?>
		<div class="item">
			<div class="info-side">
				<img class="icon" src="<?php echo htmlSpecialChars($template->userico($topic->userId, 50)) ?>" />
			</div>
			<div class="content">
				<a href="<?php echo htmlSpecialChars($presenter->link("Discussion:posts", array($topic->getId(), Leganto\DB\Post\Selector::TOPIC))) ?>
" class="topic-name"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($topic->name, ENT_NOQUOTES) ?></a>
				<br />
				<a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($topic->userId))) ?>
" title="<?php echo htmlSpecialChars($topic->userName) ?>" class="user-name"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($topic->userName, 20), ENT_NOQUOTES) ?></a>
				<br />
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Created'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->time($topic->inserted), ENT_NOQUOTES) ?>

				<br />
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Last post inserted'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->time($topic->lastPostInserted), ENT_NOQUOTES) ?>

				<br />
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Number of posts'), ENT_NOQUOTES) ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($topic->numberOfPosts, ENT_NOQUOTES) ?>

			</div>
			<div class="clear"></div>
		</div>
		<?php if ($iterator->getCounter() % 2 == 0): ?><div class="clear"></div><?php endif ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>
<hr class="cleaner" />
<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;if ($presenter->getUser()->isAllowed(Leganto\ACL\Resource::TOPIC, Leganto\ACL\Action::INSERT)): ?>
	<div id="topic-insert">
		<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Create a new topic'), ENT_NOQUOTES) ?></h2>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Before mindlessly creating a new topic please calm down. To avoid creating duplicite topic, please use search in discussion with appropriate keyword. Choose name of the new topic apposite!'), ENT_NOQUOTES) ?></p>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('For writting a good post please read also a little about'), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array(58))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('good manners'), ENT_NOQUOTES) ?></a>.</p>
<?php $_ctrl = $control->getWidget("form"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
	</div>
<?php endif ;
