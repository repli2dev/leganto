<?php //netteCache[01]000409a:2:{s:4:"time";s:21:"0.92793000 1313053012";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:87:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Twitter/Twitter.latte";i:2;i:1312991409;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Twitter/Twitter.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ri7lrx59vf')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="twitter">
	<h3><a href="http://twitter.com/#!/leganto_devel" title="<?php echo htmlSpecialChars($template->translate('Leganto on twitter')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Leganto on twitter'), ENT_NOQUOTES) ?></a></h3>

	<div id="twitter_div">
		<ul id="twitter_update_list"></ul>
	</div>
	
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/leganto_devel.json?callback=twitterCallback2&amp;count=1"></script>
</div>