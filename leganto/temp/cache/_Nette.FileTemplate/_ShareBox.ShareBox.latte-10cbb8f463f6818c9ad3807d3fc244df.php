<?php //netteCache[01]000411a:2:{s:4:"time";s:21:"0.27314200 1312986743";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:89:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/ShareBox/ShareBox.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/ShareBox/ShareBox.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '0svrqm8a07')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="share-box" class="box">
    <h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Share'), ENT_NOQUOTES) ?></h3>
	<p><em><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Show your friend your liking.'), ENT_NOQUOTES) ?></em></p>
    <div class="share-box-inner">
	    <a href="http://www.facebook.com/sharer.php?u=<?php echo htmlSpecialChars($url) ?>
&t=<?php echo htmlSpecialChars($title) ?>" title="<?php echo htmlSpecialChars($template->translate("Share on Facebook")) ?>
"><img src="/img/socialnetworks/facebook.png" alt="<?php echo htmlSpecialChars($template->translate("Share on Facebook")) ?>" /></a>
	    <a href="http://twitter.com/home?status=<?php echo htmlSpecialChars($title) ?>
 <?php echo htmlSpecialChars($url) ?> <?php echo htmlSpecialChars($template->translate("I like it!")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Share on Twitter")) ?>
" target="_blank"><img src="/img/socialnetworks/twitter.png" alt="<?php echo htmlSpecialChars($template->translate("Share on Twitter")) ?>" /></a>
	    <a href="http://del.icio.us/post?url=<?php echo htmlSpecialChars($url) ?>" title="<?php echo htmlSpecialChars($template->translate("Save to Delicious")) ?>
"><img src="/img/socialnetworks/delicious.png" alt="<?php echo htmlSpecialChars($template->translate("Save to Delicious")) ?>" /></a>
	    <a href="http://digg.com/submit?url=<?php echo htmlSpecialChars($url) ?>&amp;title=<?php echo htmlSpecialChars($title) ?>
" title="<?php echo htmlSpecialChars($template->translate("Share on Digg")) ?>"><img src="/img/socialnetworks/digg.png" alt="<?php echo htmlSpecialChars($template->translate("Share on Digg")) ?>" /></a>
	    <a href="https://www.google.com/bookmarks/mark?op=edit&bkmk=<?php echo htmlSpecialChars($url) ?>
&amp;title=<?php echo htmlSpecialChars($title) ?>" title="<?php echo htmlSpecialChars($template->translate("Bookmark on Google")) ?>
"><img src="/img/socialnetworks/google.png" alt="<?php echo htmlSpecialChars($template->translate("Bookmark on Google")) ?>" /></a>
    </div>
</div>