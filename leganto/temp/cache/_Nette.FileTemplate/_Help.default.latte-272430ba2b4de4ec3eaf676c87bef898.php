<?php //netteCache[01]000405a:2:{s:4:"time";s:21:"0.20128700 1312887274";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:83:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Help/default.latte";i:2;i:1312887273;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Help/default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '9rpqn8erlp')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbfe6571d765_content')) { function _lbfe6571d765_content($_l, $_args) { extract($_args)
?>
<div id="content">
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Help'), ENT_NOQUOTES) ?></h1>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Welcome in your support section, where you can find answers for nearly all your questions. The whole support page is divided into several categories. To continue select and click on one of categories shown below this text."), ENT_NOQUOTES) ?></p>

<?php if (count($data) > 0): ?>
		<div id="help-container">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data) as $row): ?>
			<div class="help-category" id="br5"><div class="help-category-inner">
					<h2><a href="<?php echo htmlSpecialChars($presenter->link("Help:category", array($row->id_support_category))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row->name, ENT_NOQUOTES) ?></a></h2>
					<span class="description"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row->description, ENT_NOQUOTES) ?></span>
<?php if (count($data) > 0): ?>
						<div class="cleaner">&nbsp;</div>
						<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Pages"), ENT_NOQUOTES) ?></h3>
						<p>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($content[$row->id_support_category]) as $item2): ?>
							<a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array($item2->id_support_text))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item2->name, ENT_NOQUOTES) ?></a><br />
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
						</p>
<?php endif ?>
					&nbsp;
			</div></div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
		</div>
<?php else: ?>
		<p><strong>Sorry, but there are no support pages translated into your language. We are working on it!</strong></p>
<?php endif ?>
</div>
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
$_l->extends = "../@layout.latte" ?>

<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
