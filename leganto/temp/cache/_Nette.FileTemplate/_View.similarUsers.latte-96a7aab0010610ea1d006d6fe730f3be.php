<?php //netteCache[01]000408a:2:{s:4:"time";s:21:"0.87278600 1312989484";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:86:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/similarUsers.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/similarUsers.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'gzdzpvgfgx')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<similar-users xmlns="http://leganto.com/api">

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($users) as $similarUser): ?>
	<user>
		<id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->getId(), ENT_NOQUOTES) ?></id>

		<name><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->nickname, ENT_NOQUOTES) ?></name>

		<registrated><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->inserted, ENT_NOQUOTES) ?></registrated>

<?php if (!empty($similarUser->lastLogged)): ?>
		<last-logged><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->lastLogged, ENT_NOQUOTES) ?></last-logged>
<?php endif ?>

<?php if (!empty($similarUser->birthyear)): ?>
		<birth-year><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->birthyear, ENT_NOQUOTES) ?></birth-year>
<?php endif ?>

<?php if (!empty($similarUser->sex)): ?>
		<sex><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->sex, ENT_NOQUOTES) ?></sex>
<?php endif ?>

		<similarity><?php echo Nette\Templating\DefaultHelpers::escapeHtml($similarUser->similarity*100, ENT_NOQUOTES) ?></similarity>
	</user>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>

</similar-users>