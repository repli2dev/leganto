<?php //netteCache[01]000408a:2:{s:4:"time";s:21:"0.78936200 1312989393";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:86:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/bookOpinions.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/bookOpinions.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '5cov02o823')
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

<?php if (!empty($opinions)): ?>
	<opinions>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($opinions) as $opinion): ?>
		<opinion>
			<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->getId(), ENT_NOQUOTES) ?></id>
			<user>
				<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->userId, ENT_NOQUOTES) ?></id>
				<name><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->userName, ENT_NOQUOTES) ?></name>
			</user>
			<inserted><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->inserted, ENT_NOQUOTES) ?></inserted>
			<rating><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->rating, ENT_NOQUOTES) ?></rating>
			<content><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->content, ENT_NOQUOTES) ?></content>
		</opinion>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</opinions>
<?php endif ?>

</book>