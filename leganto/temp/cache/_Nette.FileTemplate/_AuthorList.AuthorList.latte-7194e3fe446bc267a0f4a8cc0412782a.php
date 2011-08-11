<?php //netteCache[01]000415a:2:{s:4:"time";s:21:"0.99907200 1313056797";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:93:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/AuthorList/AuthorList.latte";i:2;i:1313056785;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/AuthorList/AuthorList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'fnx0bdb57x')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="author-list">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($authors) as $author): ?>
    <div class="author-item">
	<div class="info">
	    <a href="<?php echo htmlSpecialChars($presenter->link("Author:default", array($author->getId()))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->fullname, ENT_NOQUOTES) ?></a>
	</div>
	<div class="content">
		    
	</div>
    </div>
    <?php if ($iterator->getCounter() % 3 == 0): ?><hr style="clear:both; visibility:hidden" /><?php endif ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>
<hr style="clear:both; visibility:hidden" />
<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
