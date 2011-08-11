<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.20067000 1313057759";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/HelpList/HelpList.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/HelpList/HelpList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'gfst4tuttq')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="support list">

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($helps) as $help): ?>
	<div class="clear"></div>
	<div class="item">
		<div class="info-side">
			<img src="/img/ico/support.png" width="50" alt="<?php echo htmlSpecialChars($template->translate('Support')) ?>" />
			<span class="date"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($help->updated), ENT_NOQUOTES) ?></span>
		</div>
	    <div class="info-top">
			<a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array($help->getId()))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($help->name, ENT_NOQUOTES) ?></a>
	    </div>
	    <div class="content">
			<p><?php echo $template->stripTags($template->texy(Nette\Utils\Strings::truncate($help->text,300))) ?></p>
	    </div>
		<div class="clear"></div>
	</div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
    
</div>

<?php $_ctrl = $control->getWidget("paginator"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
