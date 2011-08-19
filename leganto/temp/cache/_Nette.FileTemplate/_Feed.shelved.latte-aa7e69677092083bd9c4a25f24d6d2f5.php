<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.42939600 1313673202";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/shelved.latte";i:2;i:1313580548;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/shelved.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'rupa5fvzgg')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="item">
	<div class="info-side">
		<span class="date"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($item->inserted), ENT_NOQUOTES) ?></span>
	</div>
	<div class="info-top">
<?php Nette\Latte\Macros\CoreMacros::includeTemplate("delete.latte", array('item' => $item) + $template->getParams(), $_l->templates['rupa5fvzgg'])->render() ?>
		<strong class="title">
<?php if ($content[0] == 'owned'): ?>
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->userNick, ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("have just added book"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($content[2]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[3], ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("to their library"), ENT_NOQUOTES) ?>.
<?php endif ;if ($content[0] == 'general'): ?>
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->userNick, ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("have just added book"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($content[2]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[3], ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("to shelf"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:shelves", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[1], ENT_NOQUOTES) ?></a>.
<?php endif ;if ($content[0] == 'wanted'): ?>
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->userNick, ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("wants to read"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($content[2]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[3], ENT_NOQUOTES) ?></a>.
<?php endif ;if ($content[0] == 'reading'): ?>
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->userNick, ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("have just started reading book"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($content[2]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[3], ENT_NOQUOTES) ?></a>.
<?php endif ?>
		</strong>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>