<?php //netteCache[01]000426a:2:{s:4:"time";s:21:"0.03922400 1312986744";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:103:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/RelatedBookList/RelatedBookList.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/RelatedBookList/RelatedBookList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'mrzqcdiuy4')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="related-books">
    <ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($books) as $book): ?>
	    <li><img class="rating" src="<?php echo htmlSpecialChars($baseUri) ?>img/rating_<?php echo htmlSpecialChars(round($book->rating)) ?>
.png" /> <?php echo $template->language($book->language) ?> <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($book->getId()))) ?>
" title="<?php echo htmlSpecialChars($book->title) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></a></li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
    </ul>
</div>