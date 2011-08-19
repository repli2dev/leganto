<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.40916300 1313754060";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/newOpinion.latte";i:2;i:1313580539;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/newOpinion.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '0j3ndpi0m7')
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
		<img class="icon" src="<?php echo htmlSpecialChars($template->userico($item->userId, 50)) ?>
" alt="<?php echo htmlSpecialChars($item->userNick) ?>" />
		<div class="clear"></div>
		<span class="date"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($item->inserted), ENT_NOQUOTES) ?></span>
	</div>
	<div class="info-top">
<?php Nette\Latte\Macros\CoreMacros::includeTemplate("delete.latte", array('item' => $item) + $template->getParams(), $_l->templates['0j3ndpi0m7'])->render() ?>
		<strong class="title">
			<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->userNick, ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("have just contributed new opinion on book"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($content[0]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[1], ENT_NOQUOTES) ?></a>.
		</strong>
	</div>

	<div class="content">
<?php if (!empty($content[2])): ?>
			<?php echo $template->texySafe($content[2]) ?>

<?php endif ?>
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Choosen rating"), ENT_NOQUOTES) ?>
: <img src="/img/rating_<?php echo htmlSpecialChars($content[3]) ?>.png" alt="<?php echo htmlSpecialChars($template->rating($content[3])) ?>
" title="<?php echo htmlSpecialChars($template->rating($content[3])) ?>" class="rating" /></p>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>