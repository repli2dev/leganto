<?php //netteCache[01]000400a:2:{s:4:"time";s:21:"0.79868700 1312989340";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:78:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/book.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/book.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'cqxchd13l0')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<book xmlns="http://leganto.com/api">

	<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->getId(), ENT_NOQUOTES) ?></id>

	<language><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->language, ENT_NOQUOTES) ?></language>

	<title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></title>

<?php if (!empty($book->subtitle)): ?>
	<subtitle><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ?></subtitle>
<?php endif ?>

	<authors>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($authors) as $author): ?>
		<author>
			<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->getId(), ENT_NOQUOTES) ?></id>
			<type><?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->type, ENT_NOQUOTES) ?></type>
<?php if (!empty($author->groupname)): ?>
			<groupname><?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->groupname, ENT_NOQUOTES) ?></groupname>
<?php endif ;if (!empty($author->firstname)): ?>
			<firstname><?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->firstname, ENT_NOQUOTES) ?></firstname>
<?php endif ;if (!empty($author->lastname)): ?>
			<lastname><?php echo Nette\Templating\DefaultHelpers::escapeHtml($author->lastname, ENT_NOQUOTES) ?></lastname>
<?php endif ?>
		</author>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</authors>

	<rating><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->rating, ENT_NOQUOTES) ?></rating>

	<number-of-opinions><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->numberOfOpinions, ENT_NOQUOTES) ?></number-of-opinions>

	<tags>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($tags) as $tag): if ($tag->name != NULL): ?>
			<tag><?php echo Nette\Templating\DefaultHelpers::escapeHtml($tag->name, ENT_NOQUOTES) ?></tag>
<?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</tags>

<?php if (!empty($other)): ?>
	<other-titles>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($other) as $otherBook): ?>
		<book>
			<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->getId, ENT_NOQUOTES) ?></id>

			<language><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->language, ENT_NOQUOTES) ?></language>

			<title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></title>

<?php if (!empty($book->subtitle)): ?>
			<subtitle><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ?></subtitle>
<?php endif ?>
		</book>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</other>
<?php endif ?>

</book>