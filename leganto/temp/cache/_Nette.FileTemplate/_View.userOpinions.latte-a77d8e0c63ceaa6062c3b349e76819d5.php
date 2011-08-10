<?php //netteCache[01]000408a:2:{s:4:"time";s:21:"0.43552900 1312989407";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:86:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/userOpinions.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/userOpinions.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'zk8na1qquz')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<user xmlns="http://leganto.com/api">

	<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($user->getId(), ENT_NOQUOTES) ?></id>

	<name><?php echo Nette\Templating\DefaultHelpers::escapeHtml($user->nickname, ENT_NOQUOTES) ?></name>

<?php if (!empty($opinions)): ?>
	<opinions>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($opinions) as $opinion): ?>
		<opinion>
			<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->getId(), ENT_NOQUOTES) ?></id>
			<id-book-title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->bookTitleId, ENT_NOQUOTES) ?></id-book-title>
			<title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->bookTitle, ENT_NOQUOTES) ?></title>
			<inserted><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->inserted, ENT_NOQUOTES) ?></inserted>
			<rating><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->rating, ENT_NOQUOTES) ?></rating>
			<content><?php echo Nette\Templating\DefaultHelpers::escapeHtml($opinion->content, ENT_NOQUOTES) ?></content>
		</opinion>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</opinions>
<?php endif ?>

</user>