<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.43739500 1313054220";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Help/category.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Help/category.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'aelz565gtj')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbe5ecdbc423_subcontent')) { function _lbe5ecdbc423_subcontent($_l, $_args) { extract($_args)
?>
<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($category->name, ENT_NOQUOTES) ?></h1>

<p>
<?php if (count($data) > 0): $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data) as $item): ?>
			<a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array($item->id_support_text))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->name, ENT_NOQUOTES) ?></a><br />
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;else: ?>
		<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("There are no entries in this category."), ENT_NOQUOTES) ?>

<?php endif ?>
</p>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = true; unset($_extends, $template->_extends);


if ($_l->extends) {
	ob_start();
} elseif (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
$_l->extends = "layout.latte" ?>

<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
