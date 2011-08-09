<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.06340600 1312899718";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Statistics/default.latte";i:2;i:1312899216;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Statistics/default.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'avqtm0ibw8')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb9e1556063f_subcontent')) { function _lb9e1556063f_subcontent($_l, $_args) { extract($_args)
?>

    <h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Statistics'), ENT_NOQUOTES) ?></h1>

    <h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Opinions by rating'), ENT_NOQUOTES) ?></h2>
    <div class="stat-graph">
	<img src="<?php echo htmlSpecialChars($opinionsByRating) ?>"  align="center" />
    </div>

    <h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Opinions inserted in last year'), ENT_NOQUOTES) ?></h2>
    <div class="stat-graph">
	<img src="<?php echo htmlSpecialChars($opinionsLastYear) ?>" class="stat-graph" align="center" />
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
$_l->extends = "layout.latte" ?>

<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
