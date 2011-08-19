<?php //netteCache[01]000428a:2:{s:4:"time";s:21:"0.54769900 1313673249";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:105:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookShelfControl/BookShelfControl.latte";i:2;i:1313571833;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/BookShelfControl/BookShelfControl.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'q5oduxwgqh')
;//
// block _bookShelfControl
//
if (!function_exists($_l->blocks['_bookShelfControl'][] = '_lb63e8136faf__bookShelfControl')) { function _lb63e8136faf__bookShelfControl($_l, $_args) { extract($_args); $control->validateControl('bookShelfControl')
?>
	<div id="book-shelf-control" class="box">
	    <h3><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Shelves'), ENT_NOQUOTES) ?></h3>
<?php if (!empty($shelves)): ?>
		<p><em><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Click on name of the shelf to show its content.'), ENT_NOQUOTES) ?></em></p>
		<ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($shelves) as $shelf): ?>
			<li>
			    <span class="control">
				<?php $confirm = $control->translate("Are you sure you want to remove a book from shelf %s?", $shelf->name); $link    = $control->link('removeFromShelf', $book->getId(), $shelf->getId()) ?>
				<a onclick="return confirm('<?php echo $confirm ?>')" href="<?php echo $link ?>
" class="ajax" title="<?php echo htmlSpecialChars($template->translate('Delete from shelf')) ?>">
				    <img src="<?php echo htmlSpecialChars($baseUri . '/img/ico/delete.png') ?>
" alt="<?php echo htmlSpecialChars($template->translate('Delete from shelf')) ?>" />
				</a>
			    </span>
<?php $shelfId = $shelf->getId() ?>
				<a href="<?php echo htmlSpecialChars($presenter->link("User:shelves#books-in-shelf-$shelfId", array($user->id))) ?>
" title="<?php echo htmlSpecialChars($shelf->name) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($shelf->name, 19), ENT_NOQUOTES) ?></a>
			</li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
		</ul>
<?php else: ?>
		<p><em><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Not in any shelf'), ENT_NOQUOTES) ?>
 &ndash; <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('select shelf to place into.'), ENT_NOQUOTES) ?></em></p>
		<br />
<?php endif ;$_ctrl = $control->getWidget("form"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
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
<div id="<?php echo $control->getSnippetId('bookShelfControl') ?>"><?php call_user_func(reset($_l->blocks['_bookShelfControl']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
