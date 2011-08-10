<?php //netteCache[01]000401a:2:{s:4:"time";s:21:"0.09430100 1312989564";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:79:"/home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/login.latte";i:2;i:1312987818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/ApiModule/templates/View/login.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '5b9usrsfsd')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<identity xmlns="http://leganto.com/api">

<?php if (isset($identity)): ?>
	<nick><?php echo Nette\Templating\DefaultHelpers::escapeHtml($identity->getName(), ENT_NOQUOTES) ?></nick>

	<user-id><?php echo Nette\Templating\DefaultHelpers::escapeHtml($identity->id_user, ENT_NOQUOTES) ?></user-id>
<?php endif ?>

</identity>