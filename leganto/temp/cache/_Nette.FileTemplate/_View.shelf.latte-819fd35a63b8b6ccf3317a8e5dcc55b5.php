<?php //netteCache[01]000401a:2:{s:4:"time";s:21:"0.94295600 1312989437";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:79:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/shelf.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/shelf.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'i24vupu9x4')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<shelf xmlns="http://leganto.com/api">
	<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($shelf->getId(), ENT_NOQUOTES) ?></id>
	<type><?php echo Nette\Templating\DefaultHelpers::escapeHtml($shelf->type, ENT_NOQUOTES) ?></type>
	<name><?php echo Nette\Templating\DefaultHelpers::escapeHtml($shelf->name, ENT_NOQUOTES) ?></name>
	<user>
		<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($shelf->user, ENT_NOQUOTES) ?></id>
		<name><?php echo Nette\Templating\DefaultHelpers::escapeHtml($shelf->userName, ENT_NOQUOTES) ?></name>
	</user>

<?php if (!empty($books)): ?>
	<books>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($books) as $book): ?>
		<book>
			<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->getId(), ENT_NOQUOTES) ?></id>
			<language><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->language, ENT_NOQUOTES) ?></language>
			<title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->title, ENT_NOQUOTES) ?></title>
<?php if (!empty($book->subtitle)): ?>
			<subtitle><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->subtitle, ENT_NOQUOTES) ?></subtitle>
<?php endif ?>
			<rating><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->rating, ENT_NOQUOTES) ?></rating>
			<number-of-opinions><?php echo Nette\Templating\DefaultHelpers::escapeHtml($book->numberOfOpinions, ENT_NOQUOTES) ?></number-of-opinions>
		</book>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</books>
<?php endif ?>
</shelf>