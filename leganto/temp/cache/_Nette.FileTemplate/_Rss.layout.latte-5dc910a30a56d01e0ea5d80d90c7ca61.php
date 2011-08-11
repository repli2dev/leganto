<?php //netteCache[01]000403a:2:{s:4:"time";s:21:"0.08588800 1313053920";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:81:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Rss/layout.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Rss/layout.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '3k4floqxx5')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<rss version="2.0">
    <channel>
	<title>Leganto</title>
	<link />http://<?php echo Nette\Templating\DefaultHelpers::escapeHtml($domain->uri, ENT_NOQUOTES) ?></link>
	<description></description>
	<language></language>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($items) as $item): ?>
	<item>
	    <title><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->getTitle(), ENT_NOQUOTES) ?></title>
	    <link /><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->getLink(), ENT_NOQUOTES) ?></link>
	    <description><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->getDescription(), ENT_NOQUOTES) ?></description>
	    <guid><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->getGuid(), ENT_NOQUOTES) ?></guid>
	    <pubDate><?php echo Nette\Templating\DefaultHelpers::escapeHtml(date('r',strtotime($item->getDate())), ENT_NOQUOTES) ?></pubDate>
	</item>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
    </channel>
</rss>