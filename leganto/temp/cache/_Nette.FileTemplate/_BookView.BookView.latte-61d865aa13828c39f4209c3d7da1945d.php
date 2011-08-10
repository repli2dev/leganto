<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.37857800 1312986743";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookView/BookView.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookView/BookView.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '8as1oyxg63')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="book-info">
	<!-- BOOK COVER -->
	<div id="cover">
<?php if (empty($edition)): ?>
			<img src="<?php echo htmlSpecialChars($template->bookCover($book->getId(),150)) ?>
" alt="<?php echo htmlSpecialChars($book->title) ?>" />
<?php else: ?>
			<img src="<?php echo htmlSpecialChars($template->thumbnail($edition->image,150)) ?>
" alt="<?php echo htmlSpecialChars($book->title) ?>" <?php if (empty($edition->image)): ?>
width="150" height="240"<?php endif ?> />
<?php endif ?>
		<strong title="<?php echo htmlSpecialChars($template->translate("The percentual rating of the book (based on users rating)")) ?>
" class="rating"><?php if ($book->numberOfReaders > 0): echo Nette\Templating\DefaultHelpers::escapeHtml(round($book->rating/5*100), ENT_NOQUOTES) ?>
%<?php endif ?></strong>
		<div class="stats">
			<span title="<?php echo htmlSpecialChars($template->translate("Number of readers")) ?>
" class="readers"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->numberOfReaders, ENT_NOQUOTES) ?></span>
			<span title="<?php echo htmlSpecialChars($template->translate("Number of opinions (users are allowed to let opinion empty)")) ?>
" class="opinions"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->numberOfOpinions, ENT_NOQUOTES) ?></span>
		</div>
	</div>
	<!-- BOOK TITLE -->
	<h1 title="<?php echo htmlSpecialChars($template->translate("Title of book")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></h1>
	<?php if ($book->subtitle != NULL): ?><h2 title="<?php echo htmlSpecialChars($template->translate("Subtitle of book")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ?>
</h2><?php endif ?>

	<!-- STATISTICS -->
<?php $_ctrl = $control->getWidget("bookStatistics"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>

	<!-- AUTHORS -->
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($authors) as $author): ?>
		<h2>
			<a href="<?php echo htmlSpecialChars($presenter->link("Author:default", array($author->getId()))) ?>
" title="<?php echo htmlSpecialChars($author->fullname) ?>">
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->fullname, ENT_NOQUOTES) ?>

			</a>
		</h2>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

	<!-- EDITION INFO -->
<?php if (!empty($edition)): ?>
		<p>
<?php if ($edition->isbn10 != NULL): ?>
				<strong><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('ISBN'), ENT_NOQUOTES) ?>:</strong>&nbsp;
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($edition->isbn10, ENT_NOQUOTES) ?>

				<br />
<?php endif ;if ($edition->isbn13 != NULL): ?>
				<strong><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('ISBN'), ENT_NOQUOTES) ?>:</strong>&nbsp;
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($edition->isbn13, ENT_NOQUOTES) ?>

				<br />
<?php endif ;if ($edition->pages != NULL): ?>
				<strong><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Pages'), ENT_NOQUOTES) ?>
:</strong>&nbsp;<?php echo Nette\Templating\DefaultHelpers::escapeHtml($edition->pages, ENT_NOQUOTES) ?>

				<br />
<?php endif ;if ($edition->published): ?>
				<strong><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Published'), ENT_NOQUOTES) ?>
:</strong>&nbsp;<?php echo Nette\Templating\DefaultHelpers::escapeHtml($edition->published, ENT_NOQUOTES) ?>

<?php endif ?>
		</p>
<?php endif ?>
</div>
<div class="clear"></div>
    