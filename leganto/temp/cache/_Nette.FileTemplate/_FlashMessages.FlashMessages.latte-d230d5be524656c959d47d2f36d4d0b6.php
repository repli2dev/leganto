<?php //netteCache[01]000421a:2:{s:4:"time";s:21:"0.12535700 1312882854";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:99:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/FlashMessages/FlashMessages.latte";i:2;i:1312882853;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/FlashMessages/FlashMessages.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'c0eek5x0qc')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="flashes">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($control->getParent()->getTemplate()->flashes) as $flash): ?>
    <div class="flash <?php echo htmlSpecialChars($flash->type) ?>">
	    <?php echo $flash->message ?>

	</div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>