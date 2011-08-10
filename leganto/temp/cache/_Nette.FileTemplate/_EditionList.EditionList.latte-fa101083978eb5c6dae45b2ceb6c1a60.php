<?php //netteCache[01]000417a:2:{s:4:"time";s:21:"0.10830400 1312986744";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:95:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/EditionList/EditionList.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/EditionList/EditionList.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'f44oitgc4u')
;//
// block _editions
//
if (!function_exists($_l->blocks['_editions'][] = '_lba8166695e8__editions')) { function _lba8166695e8__editions($_l, $_args) { extract($_args); $control->validateControl('editions')
;if (!empty($editions)): ?>
	<script>
	$(function(){
	    //Get our elements for faster access and set overlay width
	    var div = $('#editions');
	    var ul = $('#editions ul');
	    // unordered list's left margin
	    var ulPadding = 15;

	    //Get menu width
	    var divWidth = div.width();

	    //Remove scrollbars
	    div.css({ overflow: 'hidden'});

	    //Find last image container
	    var lastLi = ul.find('li:last-child');

	    //When user move mouse over menu
	    div.mousemove(function(e){

	      //As images are loaded ul width increases,
	      //so we recalculate it each time
	      var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;

	      var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
	      div.scrollLeft(left);
	    });
	});
	</script>
	<div id="editions">
	    <ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($editions) as $edition): ?>
		    <li>
			    <?php $clrf = "\n" ?>
			    <a title="<?php echo htmlSpecialChars($template->translate("ISBN")) ?>: <?php echo htmlSpecialChars($edition->isbn10) ;echo htmlSpecialChars($clrf) ;echo htmlSpecialChars($template->translate("ISBN")) ?>
: <?php echo htmlSpecialChars($edition->isbn13) ;echo htmlSpecialChars($clrf) ;echo htmlSpecialChars($template->translate("Pages")) ?>
: <?php echo htmlSpecialChars($edition->pages) ;echo htmlSpecialChars($clrf) ;echo htmlSpecialChars($template->translate("Published")) ?>
: <?php echo htmlSpecialChars($edition->published) ?>" href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($edition->idBookTitle, $edition->getId()))) ?>">
			    <img class="icon" src="<?php echo htmlSpecialChars($template->thumbnail($edition->image, 50)) ?>" width="50" height="80" />
			</a>
		    </li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
	    </ul>
	</div>
	<div class="clear"></div>
<?php endif ;if (!$showWithoutCover): if ($hidden): ?>
	    <a href="<?php echo htmlSpecialChars($control->link("showWithoutCover")) ?>
" class="ajax" title="<?php echo htmlSpecialChars($template->translate('Show editions without an image')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Show editions without an image'), ENT_NOQUOTES) ?></a>
<?php endif ;else: ?>
	<a href="<?php echo htmlSpecialChars($control->link("hideWithoutCover")) ?>" class="ajax" title="<?php echo htmlSpecialChars($template->translate('Hide editions without an image')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Hide editions without an image'), ENT_NOQUOTES) ?></a>
<?php endif ;
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
<div id="<?php echo $control->getSnippetId('editions') ?>"><?php call_user_func(reset($_l->blocks['_editions']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
