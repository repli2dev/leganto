<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.50351300 1313673193";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Preview/Preview.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Preview/Preview.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'y8rqbnwk0q')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="books list">
	<div><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('These are opinions on latest books, join our community and show your opinion.'), ENT_NOQUOTES) ?></div>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($books) as $book): ?>
	<div class="item">
		<div class="info-side">
			<img class="icon" src="<?php echo htmlSpecialChars($template->bookCover($book->getId(),50)) ?>
" alt="<?php echo htmlSpecialChars($book->title) ?>" width="50" height="80" />
		</div>
		<div class="info-top">
			<a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($book->getId()))) ?>
" title="<?php echo htmlSpecialChars($book->title) ;if (!empty($book->subtitle)): ?>
: <?php echo htmlSpecialChars($book->subtitle) ;endif ?>" class="book-title"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ;if (!empty($book->subtitle)): ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ;endif ?></a>
		</div>
		<div class="content">
		<strong><a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($opinions[$book->getId()]->userId))) ?>
" title="<?php echo htmlSpecialChars($opinions[$book->getId()]->userName) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinions[$book->getId()]->userName, ENT_NOQUOTES) ?></a></strong>:
		<?php echo $template->stripTags($template->texySafe(\Nette\Utils\Strings::truncate($opinions[$book->getId()]->content,120))) ?>

		</div>
		<div class="clear"></div>
	</div>
	<?php if ($iterator->getCounter() % 3 == 0): ?><div class="clear"></div><?php endif ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>
