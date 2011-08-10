<?php //netteCache[01]000407a:2:{s:4:"time";s:21:"0.12232000 1312989382";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:85:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/discussions.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/discussions.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '88lm4n4q4e')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<discussions xmlns="http://leganto.com/api">

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($discussions) as $discussion): ?>
	<discussion>
		<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->getId(), ENT_NOQUOTES) ?></id>
		<name><?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->name, ENT_NOQUOTES) ?></name>
		<inserted><?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->inserted, ENT_NOQUOTES) ?></inserted>
		<number-of-posts><?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->numberOfPosts, ENT_NOQUOTES) ?></number-of-posts>
		<last-post-inserted><?php echo Nette\Templating\DefaultHelpers::escapeHtml($discussion->lastPostInserted, ENT_NOQUOTES) ?></last-post-inserted>
	</discussion>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

</discussions>