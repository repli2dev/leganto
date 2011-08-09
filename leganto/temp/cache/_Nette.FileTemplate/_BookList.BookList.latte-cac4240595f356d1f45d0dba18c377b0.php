<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.83491600 1312888724";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookList/BookList.latte";i:2;i:1312887500;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookList/BookList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'y4is0t7ll7')
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
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($books) as $book): ?>
		<div class="item">
			<div class="info-side">
				<img class="icon" src="<?php echo htmlSpecialChars($template->bookCover($book->getId(),50)) ?>
" alt="<?php echo htmlSpecialChars($book->title) ?>" width="50" height="80" />
			</div>
			<div class="info-top">
				<a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($book->getId()))) ?>
" title="<?php echo htmlSpecialChars($book->title) ;if ($book->subtitle !== NULL): ?>
: <?php echo htmlSpecialChars($book->subtitle) ;endif ?>" class="book-title"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></a>
				<br /><em><?php if ($book->subtitle !== NULL): echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ;endif ?></em>
			</div>
			<div class="content">
<?php if (count($authors[$book->bookNode]) > 2): ?>
				<div class="authors-hide">
					<span>
<?php endif ;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($authors[$book->bookNode]) as $author): ?>
						<a href="<?php echo htmlSpecialChars($presenter->link("Author:default", array($author->getId()))) ?>
" title="<?php echo htmlSpecialChars($author->fullname) ?>" class="author">
						<?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->fullname, ENT_NOQUOTES) ?>

						</a>
						<br />
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;if (count($authors[$book->bookNode]) > 2): ?>
					</span>
				</div>
<?php endif ;if ($book->numberOfReaders > 0): ?>
					<img src="<?php echo htmlSpecialChars($baseUri) ?>img/rating_<?php echo htmlSpecialChars(round($book->rating)) ?>
.png" alt="<?php echo htmlSpecialChars($template->rating(round($book->rating))) ?>
" title="<?php echo htmlSpecialChars($template->rating(round($book->rating))) ?>" class="rating" />
<?php else: ?>
					<img src="<?php echo htmlSpecialChars($baseUri) ?>img/rating_na.png" alt="<?php echo htmlSpecialChars($template->translate('Rating not available')) ?>
" title="<?php echo htmlSpecialChars($template->translate('Rating not available')) ?>" class="rating" />
<?php endif ?>
			</div>

			<div class="tags">
<?php if (isset($tags[$book->bookNode])): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($tags[$book->bookNode]) as $tag): ?>
				<a href="" class="tag">
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($tag->name, ENT_NOQUOTES) ?>

				</a>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;endif ?>
			</div>
		</div>
		<?php if ($iterator->getCounter() % 3 == 0): ?><div class="clear"></div><?php endif ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>
<hr style="clear:both; visibility:hidden" />
<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
