<?php //netteCache[01]000406a:2:{s:4:"time";s:21:"0.36206600 1313052887";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:84:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/newPost.latte";i:2;i:1312990841;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/newPost.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'hol4g1h0ny')
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
		<strong class="title">
			<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($item->userId))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($item->userNick, ENT_NOQUOTES) ?>
</a> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("have just contributed new post to discussion"), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Discussion:posts", array($content[3],$content[2]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($content[1], ENT_NOQUOTES) ?></a>:
		</strong>
	</div>

	<div class="content">
		<?php echo $template->texySafe($content[5]) ?>

	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>