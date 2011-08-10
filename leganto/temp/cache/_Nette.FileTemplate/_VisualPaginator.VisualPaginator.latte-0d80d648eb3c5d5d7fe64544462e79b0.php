<?php //netteCache[01]000426a:2:{s:4:"time";s:21:"0.85109600 1312986685";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:103:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/VisualPaginator/VisualPaginator.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/VisualPaginator/VisualPaginator.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'x691fr6qmg')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
if ($paginator->pageCount > 1): ?>
<div class="paginator">
<?php if ($paginator->isFirst()): ?>
	<span class="button">« <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Previous'), ENT_NOQUOTES) ?></span>
<?php else: ?>
	<a href="<?php echo htmlSpecialChars($control->link("this", array('page' => $paginator->page - 1))) ?>
">« <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Previous'), ENT_NOQUOTES) ?></a>
<?php endif ?>

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($steps) as $step): if ($step == $paginator->page): ?>
		<span class="current"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($step, ENT_NOQUOTES) ?></span>
<?php else: ?>
		<a href="<?php echo htmlSpecialChars($control->link("this", array('page' => $step))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($step, ENT_NOQUOTES) ?></a>
<?php endif ?>
	<?php if ($iterator->nextValue > $step + 1): ?><span>…</span><?php endif ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

<?php if ($paginator->isLast()): ?>
	<span class="button"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Next'), ENT_NOQUOTES) ?> »</span>
<?php else: ?>
	<a href="<?php echo htmlSpecialChars($control->link("this", array('page' => $paginator->page + 1))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Next'), ENT_NOQUOTES) ?> »</a>
<?php endif ?>
</div>
<?php endif ;
