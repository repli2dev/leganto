<?php //netteCache[01]000415a:2:{s:4:"time";s:21:"0.74222500 1313673249";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:93:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookMerger/BookMerger.latte";i:2;i:1313491465;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookMerger/BookMerger.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '8lpb9ni6ev')
;//
// block _bookMerger
//
if (!function_exists($_l->blocks['_bookMerger'][] = '_lb31e2450194__bookMerger')) { function _lb31e2450194__bookMerger($_l, $_args) { extract($_args); $control->validateControl('bookMerger')
?>
<div class="box" id="book-merger-box">
	<h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Merge books'), ENT_NOQUOTES) ?></h3>
	<p><em><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Be sure what are you doing!'), ENT_NOQUOTES) ?></em></p>
	<ul>
<?php if (!empty($master)): ?>
			<li class="master">
				<?php $confirm = $control->translate("Are you sure you want to remove the book [%s] from merging?", $master->title) ?>
				<a onclick="return confirm('<?php echo $confirm ?>')" href="<?php echo htmlSpecialChars($control->link("removeMasterBook")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Remove the book from merging')) ?>">
					<img src="<?php echo htmlSpecialChars($baseUri . '/img/ico/delete.png') ?>" alt="<?php echo htmlSpecialChars($template->translate('Remove the book from merging')) ?>" />
				</a>
				<a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($master->getId()))) ?>
" title="<?php echo htmlSpecialChars($master->title . ($master->subtitle != NULL ? ': ' . $master->subtitle : '')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($master->title, 20), ENT_NOQUOTES) ?></a>
			</li>
<?php endif ;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($slave) as $b): ?>
			<?php $confirm = $control->translate("Are you sure you want to remove the book [%s] from merging?", $b->title) ?>
			<li class="slave">
				<a onclick="return confirm('<?php echo $confirm ?>')" href="<?php echo htmlSpecialChars($control->link("removeSlaveBook", array($b->getId()))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Remove the book from merging')) ?>">
					<img src="<?php echo htmlSpecialChars($baseUri . '/img/ico/delete.png') ?>" alt="<?php echo htmlSpecialChars($template->translate('Remove the book from merging')) ?>" />
				</a>
				<a href="<?php echo htmlSpecialChars($presenter->link("Book:default", array($b->getId()))) ?>
" title="<?php echo htmlSpecialChars($b->title . ($b->subtitle != NULL ? ': ' . $b->subtitle : '')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($b->title, 20), ENT_NOQUOTES) ?></a>
			</li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;if ((empty($master) || $master->getId() != $book->getId()) && !in_array($book->getId(), array_keys($slave))): ?>
			<li><a href="<?php echo htmlSpecialChars($control->link("setMasterBook", array($book->getId()))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Set as a master book')) ?>
" class="ajax"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Set as a master book'), ENT_NOQUOTES) ?></a></li>
			<li><a href="<?php echo htmlSpecialChars($control->link("addSlaveBook", array($book->getId()))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Add as a slave book')) ?>
" class="ajax"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Add as a slave book'), ENT_NOQUOTES) ?></a></li>
<?php endif ;if (!empty($master) && !empty($slave)): ?>
			<?php $confirm = $control->translate("Are you sure you want to merge the books?") ?>
			<li>
				<a onclick="return confirm('<?php echo $confirm ?>')" href="<?php echo htmlSpecialChars($control->link("merge")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Merge')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Merge'), ENT_NOQUOTES) ?></a>
			</li>
<?php endif ?>
	</ul>
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
<div id="<?php echo $control->getSnippetId('bookMerger') ?>"><?php call_user_func(reset($_l->blocks['_bookMerger']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
