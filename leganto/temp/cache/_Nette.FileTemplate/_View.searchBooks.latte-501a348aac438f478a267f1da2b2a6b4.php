<?php //netteCache[01]000407a:2:{s:4:"time";s:21:"0.97794900 1312989537";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:85:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/searchBooks.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/searchBooks.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '0a2e6e6y8l')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<books xmlns="http://leganto.com/api">
		
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($books) as $book): ?>
	<book>

		<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->getId(), ENT_NOQUOTES) ?></id>
	
		<title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></title>
	
<?php if (!empty($book->subtitle)): ?>
		<subtitle><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ?></subtitle>
<?php endif ?>
	
		<authors>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($authors[$book->getId()]) as $author): ?>
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
		
	</book>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

</books>