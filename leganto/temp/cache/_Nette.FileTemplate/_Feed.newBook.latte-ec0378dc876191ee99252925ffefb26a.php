<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.87943000 1313053083";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/newBook.latte";i:2;i:1312990841;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/newBook.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'vx4b8mimdt')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="item item-compact">
	<div class="info-side">
		<span class="date"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($item->inserted), ENT_NOQUOTES) ?></span>
	</div>
	<div class="info-top">
		<strong class="title">
			<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("The book"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($content[0]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[1], ENT_NOQUOTES) ;if (isSet($content[2]) && !empty($content[2])): ?>
: <?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[2], ENT_NOQUOTES) ;endif ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("has been added."), ENT_NOQUOTES) ?>

		</strong>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>