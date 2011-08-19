<?php //netteCache[01]000415a:2:{s:4:"time";s:21:"0.82894900 1313673192";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:93:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Navigation/Navigation.latte";i:2;i:1313051946;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Navigation/Navigation.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'yb6kstkhm2')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<div id="navbar">
	<div class="wrapper">
		<ul id="menu">
			<li><a href="<?php echo htmlSpecialChars($presenter->link("default:default")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Main page')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Main page'), ENT_NOQUOTES) ?></a></li>
			<li><a href="<?php echo htmlSpecialChars($presenter->link("Discussion:default")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Discussion')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Discussion'), ENT_NOQUOTES) ?></a></li>
<?php if ($presenter->getUser()->isAllowed(\Leganto\ACL\Resource::BOOK, \Leganto\ACL\Action::INSERT)): ?>
			    <li><a href="<?php echo htmlSpecialChars($presenter->link("Book:insert")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Insert book')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Insert book'), ENT_NOQUOTES) ?></a></li>
<?php endif ?>
			<li><a href="<?php echo htmlSpecialChars($presenter->link("Default:topBooks")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Top books')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Top books'), ENT_NOQUOTES) ?></a></li>
			<li><a href="<?php echo htmlSpecialChars($presenter->link("Help:default")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Help')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Help'), ENT_NOQUOTES) ?></a></li>
			<!-- <li><a href="<?php echo Nette\Templating\DefaultHelpers::escapeHtmlComment($presenter->link("Statistics:default")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtmlComment($template->translate('Statistics')) ?></a></li> -->
		</ul>
		<div id="user">
<?php if ($user->isLoggedIn()): ?>
				<a href="<?php echo htmlSpecialChars($presenter->link("User:default", array($user->getId()))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Show my profile')) ?>">
					<img src="/img/ico/reader.png" alt="<?php echo htmlSpecialChars($template->translate('Your profile')) ?>" />
					<span><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->hardTruncate($nickname, 20), ENT_NOQUOTES) ?></span>
				</a>
				<a href="<?php echo htmlSpecialChars($presenter->link("Settings:")) ?>" title="<?php echo htmlSpecialChars($template->translate('Edit settings')) ?>
"><img src="/img/ico/settings.png" /><span><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Settings'), ENT_NOQUOTES) ?></span></a>
				<a class="logout" href="<?php echo htmlSpecialChars($control->link("logout")) ?>
" title="<?php echo htmlSpecialChars($template->translate('Log out')) ?>"><img src="/img/ico/logout.png" /></a>
<?php else: ?>
		<a href="<?php echo htmlSpecialChars($presenter->link('Default:',TRUE)) ?>" title="<?php echo htmlSpecialChars($template->translate("Shows form for login.")) ?>">
					<img src="/img/ico/reader.png" alt="<?php echo htmlSpecialChars($template->translate('Log in')) ?>" />
					<span><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Log in"), ENT_NOQUOTES) ?></span>
				</a>
<?php endif ?>
		</div>
	</div>
</div>