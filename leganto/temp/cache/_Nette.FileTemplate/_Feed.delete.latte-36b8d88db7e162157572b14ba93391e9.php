<?php //netteCache[01]000405a:2:{s:4:"time";s:21:"0.19399600 1313673202";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:83:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/delete.latte";i:2;i:1313581312;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Feed/delete.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'zebfqp6512')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
if ($presenter->getUser()->isAllowed(Leganto\ACL\Resource::create($item), Leganto\ACL\Action::EDIT)): ?>
	<div class="control">
		<?php $confirm = $template->translate("Are you sure you want to delete this feed item?"); $link    = $control->link('delete', $item->getId()) ?>
		<a onclick="return confirm('<?php echo $confirm ?>')" href="<?php echo $link ?>
" class="ajax" title="<?php echo htmlSpecialChars($template->translate('Delete')) ?>">
			<img src="<?php echo htmlSpecialChars($baseUri . '/img/ico/delete.png') ?>" alt="<?php echo htmlSpecialChars($template->translate('Delete')) ?>" />
		</a>
	</div>
<?php endif ;
