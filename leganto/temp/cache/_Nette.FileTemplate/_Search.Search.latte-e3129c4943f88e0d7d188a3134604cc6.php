<?php //netteCache[01]000407a:2:{s:4:"time";s:21:"0.16788400 1312986682";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:85:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Search/Search.latte";i:2;i:1312986653;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Search/Search.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'iit4doj16q')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo $form->render('begin') ?>

    <fieldset>
	<legend></legend>
	<?php echo $form['query']->control ?>

	<?php echo $form['search_submit']->control ?>

	<div id="search-options" title="<?php echo htmlSpecialChars($template->translate('Choose in which content you want to search.')) ?>">
		<?php echo $form["search"]->control ?>

		&nbsp;
	</div>
    </fieldset>
<?php echo $form->render('end') ;
