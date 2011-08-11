<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.80891700 1313054312";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Submenu/Submenu.latte";i:2;i:1313054311;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Submenu/Submenu.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'racimyrjm5')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="box" id="submenu">
    <ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($links) as $link): if ($link->getArgs()): ?>
	    <li<?php if ($presenter->getAction() == $link->getAction() && $control->equalArgs($link->getArgs(),$presenter)): ?>
 class="active"<?php endif ?>><a href="<?php echo htmlSpecialChars($presenter->link($link->getAction(),$link->getArgs())) ?>
" title="<?php echo htmlSpecialChars($link->getTitle()) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($link->getName(), ENT_NOQUOTES) ?></a></li>
<?php else: ?>
	    <li<?php if ($presenter->getAction() == $link->getAction()): ?> class="active"<?php endif ?>
><a href="<?php echo htmlSpecialChars($presenter->link($link->getAction())) ?>" title="<?php echo htmlSpecialChars($link->getTitle()) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($link->getName(), ENT_NOQUOTES) ?></a></li>
<?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
    </ul>
</div>

<?php if (!empty($events)): ?>
<div class="box" id="actions">
    <ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($events) as $link): $url    = $control->getPresenter()->link($link->getAction(), $link->getArgs()) ?>
			<li class="event <?php if ($presenter->getAction() == $link->getAction()): ?>
active<?php endif ?>">
					<a href="<?php echo $url ?>" title="<?php echo htmlSpecialChars($link->getTitle()) ?>
" <?php if ($link->getConfirm() != NULL): ?>onclick="return (confirm('<?php echo $link->getConfirm() ?>
'))"<?php endif ?>><?php echo Nette\Templating\DefaultHelpers::escapeHtml($link->getName(), ENT_NOQUOTES) ?></a>
			</li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
    </ul>
</div>
<?php endif ;
