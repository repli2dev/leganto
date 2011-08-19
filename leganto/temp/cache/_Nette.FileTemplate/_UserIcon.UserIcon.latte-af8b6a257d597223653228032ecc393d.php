<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.89147300 1313673206";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/UserIcon/UserIcon.latte";i:2;i:1313512002;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/UserIcon/UserIcon.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '6tn573yi01')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div class="box" id="user-icon">
	<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("User's icon"), ENT_NOQUOTES) ?></h3>
	<div id="user-icon-img">
		<a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($user->getId()))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Readers')) ?> &ndash; Leganto">
			<img src="<?php echo htmlSpecialChars($presenter->link("User:icon", array($user->getId()))) ?>
" alt="<?php echo htmlSpecialChars($template->translate('Readers')) ?> &ndash; Leganto" />
		</a>
	</div>
	<div id="user-icon-src">
		&lt;a&nbsp;href="http://www.leganto.cz/user/?user=<?php echo Nette\Templating\DefaultHelpers::escapeHtml($user->getId(), ENT_NOQUOTES) ?>
" title="<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Readers'), ENT_NOQUOTES) ?> &ndash; Leganto"&gt;
		&lt;img&nbsp;src="http://www.leganto.cz/user/icon/?id=<?php echo Nette\Templating\DefaultHelpers::escapeHtml($user->getId(), ENT_NOQUOTES) ?>
" alt="<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Readers'), ENT_NOQUOTES) ?> &ndash; normal" /&gt;&lt;/a&gt;
	</div>
	<div id="user-icon-img">
		<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('If you demand more interactivity, try'), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array(61))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('read books'), ENT_NOQUOTES) ?>
</a>. <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('It offers more interactive block for embeddeding into webpage.'), ENT_NOQUOTES) ?></p>
	</div>
</div>