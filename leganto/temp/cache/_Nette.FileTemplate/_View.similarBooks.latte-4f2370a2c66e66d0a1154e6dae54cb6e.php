<?php //netteCache[01]000408a:2:{s:4:"time";s:21:"0.23228700 1312989452";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:86:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/similarBooks.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/similarBooks.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'rfu6v0mxye')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<similar-books xmlns="http://leganto.com/api">
	
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($similar) as $similarBook): ?>
	<book>
		<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarBook->getId(), ENT_NOQUOTES) ?></id>
		<language><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarBook->language, ENT_NOQUOTES) ?></language>
		<title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarBook->title, ENT_NOQUOTES) ?></title>
<?php if (!empty($similarBook->subtitle)): ?>
		<subtitle><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarBook->subtitle, ENT_NOQUOTES) ?></subtitle>
<?php endif ?>
		<rating><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarBook->rating, ENT_NOQUOTES) ?></rating>
		<number-of-opinions><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarBook->numberOfOpinions, ENT_NOQUOTES) ?></number-of-opinions>
	</book>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

</similar-books>