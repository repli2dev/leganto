<?php //netteCache[01]000424a:2:{s:4:"time";s:21:"0.90130700 1313673249";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:101:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookStatistics/BookStatistics.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookStatistics/BookStatistics.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'zv20g8a6v0')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<script type="text/javascript">
$(document).ready(
    function() {
	$("#book-stats").accordion({
	    event: "mouseover",
	    autoHeight: false
	});
    }
);
</script>
<div>
<div id="book-stats" title="<?php echo htmlSpecialChars($template->translate("Visualized information, move on title to switch view.")) ?>">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($graphs) as $graph): if (!empty($graph['graph'])): ?>
	    <h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($graph['label'], ENT_NOQUOTES) ?></h3>
	    <div>
		<img src="<?php echo htmlSpecialChars($graph['graph']->getLink()) ?>" alt="<?php echo htmlSpecialChars($graph['label']) ?>" />
	    </div>
<?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
</div>
</div>