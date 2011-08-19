<?php //netteCache[01]000421a:2:{s:4:"time";s:21:"0.86925200 1313673192";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:99:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/FlashMessages/FlashMessages.latte";i:2;i:1313585033;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/FlashMessages/FlashMessages.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'dmqplmmahq')
;//
// block _flashes
//
if (!function_exists($_l->blocks['_flashes'][] = '_lbfb79258158__flashes')) { function _lbfb79258158__flashes($_l, $_args) { extract($_args); $control->validateControl('flashes')
?>
	<div id="flashes">
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($control->getParent()->getTemplate()->flashes) as $flash): ?>
	    <div class="flash <?php echo htmlSpecialChars($flash->type) ?>">
		    <?php echo $flash->message ?>

		    <a class="flash-hide ajax" href="<?php echo htmlSpecialChars($control->link("hide!")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Hide all"), ENT_NOQUOTES) ?></a>
		</div>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	</div>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extends) ? FALSE : $template->_extends; unset($_extends, $template->_extends);


if ($_l->extends) {
	ob_start();
} elseif (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="<?php echo $control->getSnippetId('flashes') ?>"><?php call_user_func(reset($_l->blocks['_flashes']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
