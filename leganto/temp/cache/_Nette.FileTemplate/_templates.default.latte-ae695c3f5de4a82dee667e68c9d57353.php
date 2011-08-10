<?php //netteCache[01]000399a:2:{s:4:"time";s:21:"0.49763300 1312990202";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:77:"/home/Weby/Ostatni/preader/www/leganto/app/CronModule/templates/default.latte";i:2;i:1312989845;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/CronModule/templates/default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'pl84japkm2')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
$timer = Nette\Diagnostics\Debugger::timer("cron") ;if ($timer != 0): ?>
<p>
	<strong>[elapsed time]</strong>  <?php echo Nette\Templating\DefaultHelpers::escapeHtml($timer, ENT_NOQUOTES) ?>

</p>
<?php endif ;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($flashes) as $flash): ?>
<p>
	<strong>[<?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->type, ENT_NOQUOTES) ?>
]</strong>  <?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->message, ENT_NOQUOTES) ?>

</p>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;
