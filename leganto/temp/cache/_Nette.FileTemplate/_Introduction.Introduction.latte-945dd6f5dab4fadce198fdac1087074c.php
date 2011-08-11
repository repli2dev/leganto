<?php //netteCache[01]000419a:2:{s:4:"time";s:21:"0.64991700 1313051819";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:97:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Introduction/Introduction.latte";i:2;i:1313051818;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/Introduction/Introduction.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '65v3wyrsry')
;//
// block _introductionBlock
//
if (!function_exists($_l->blocks['_introductionBlock'][] = '_lbced35ec7e0__introductionBlock')) { function _lbced35ec7e0__introductionBlock($_l, $_args) { extract($_args); $control->validateControl('introductionBlock')
?>
	<div id="introduction">
<?php if (isset($hint)): ?>
		<div id="virtual-tour">
			<div class="slider">
				<img src="/img/tour/<?php echo $language->locale ?>/opinions.png" alt="<?php echo htmlSpecialChars($template->translate("Opinions on books")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Discover what is opinion of other people, keep track your and your friends books.")) ?>" />
				<img src="/img/tour/<?php echo $language->locale ?>/similar-books.png" alt="<?php echo htmlSpecialChars($template->translate("Similar books")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Did you like the book and want to read something similar? See similar books.")) ?>" />
				<img src="/img/tour/<?php echo $language->locale ?>/similar-users.png" alt="<?php echo htmlSpecialChars($template->translate("Similar users")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Can't decide what to read, look what users with similar ranking read.")) ?>" />
				<img src="/img/tour/<?php echo $language->locale ?>/graphs.png" alt="<?php echo htmlSpecialChars($template->translate("Graphs")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Want quick overview of book's target group? Or distribution of ratings? See our graphs.")) ?>" />
				<img src="/img/tour/<?php echo $language->locale ?>/shelves.png" alt="<?php echo htmlSpecialChars($template->translate("Shelves for books")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Want track your library or just list books you want to read? Just put it into shelf.")) ?>" />
				<img src="/img/tour/<?php echo $language->locale ?>/messaging.png" alt="<?php echo htmlSpecialChars($template->translate("Private messages")) ?>
" title="<?php echo htmlSpecialChars($template->translate("Want to keep in touch with other users? Just send them message.")) ?>" />
			</div>
		</div>
<?php else: ?>
				
<?php if ($state == "login"): ?>
				<div id="introduction-form-wrapper">
<?php if ($flashes): ?>
				<ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($flashes) as $flash): ?>
						<li><?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
				</ul>
<?php endif ;$_ctrl = $control->getWidget("loginForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
				</div>
<?php endif ;if ($state == "facebook"): if ($enabled): ?>
					<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Do you have an account?'), ENT_NOQUOTES) ?></h1>
					<div id="mentioned-yes">
						<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Yes...'), ENT_NOQUOTES) ?></h2>
						<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('If you want to connect this Facebook account please log in.'), ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("facebookForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
					</div>
					<div id="mentioned-no">
						<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('No...'), ENT_NOQUOTES) ?></h2>
						<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("If you want to create a new account please click on the button 'Create' which logs you in automatically in just a second."), ENT_NOQUOTES) ?></p>
						<ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($flashes) as $flash): ?>
								<li><?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
						</ul>
						<a href="<?php echo htmlSpecialChars($control->link("signUpViaFacebook")) ?>
" id="signup_button"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Create'), ENT_NOQUOTES) ?></a>
					</div>
<?php endif ;endif ;if ($state == "twitter"): if ($enabled): ?>
					<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Do you have an account?'), ENT_NOQUOTES) ?></h1>
					<div id="mentioned-yes">
						<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Yes...'), ENT_NOQUOTES) ?></h2>
						<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('If you want to connect this Twitter account please log in.'), ENT_NOQUOTES) ?></p>
<?php $_ctrl = $control->getWidget("twitterForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
					</div>
					<div id="mentioned-no">
						<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('No...'), ENT_NOQUOTES) ?></h2>
						<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("If you want to create a new account please click on the button 'Create' which logs you in automatically in just a second."), ENT_NOQUOTES) ?></p>
						<ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($flashes) as $flash): ?>
								<li><?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
						</ul>
						<a href="<?php echo htmlSpecialChars($control->link("signUpViaTwitter")) ?>
" id="signup_button"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Create'), ENT_NOQUOTES) ?></a>
					</div>
					<div class="clear"></div>
<?php endif ;endif ;if ($state == "signup"): ?>
				<div id="introduction-form-wrapper">
<?php $_ctrl = $control->getWidget("signUpForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
				</div>
<?php endif ;if ($state == "forgotten"): ?>
				<div id="introduction-form-wrapper">
<?php $_ctrl = $control->getWidget("forgottenForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
				</div>
<?php endif ;if ($state == "renew"): ?>
				<div id="introduction-form-wrapper">
<?php if ($flashes): ?>
				<ul>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($flashes) as $flash): ?>
						<li><?php echo Nette\Templating\DefaultHelpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></li>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
				</ul>
<?php endif ;$_ctrl = $control->getWidget("renewForm"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
				</div>
<?php endif ;endif ?>

<?php if ($state != "facebook" && $state != "twitter"): ?>
			<div id="info">
				<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Welcome!'), ENT_NOQUOTES) ?></h1>
				<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Welcome text. Put introduction text here!'), ENT_NOQUOTES) ?>
<br /><a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array(31))) ?>
" title="<?php echo htmlSpecialChars($template->translate('What we aim on and how it works.')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Learn how it works'), ENT_NOQUOTES) ?></a></p>
				<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2F%23%21%2Fpages%2FCtenari%2F137156876307890%3Fref%3Dts&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; margin-top: 20px; height:80px;" allowTransparency="true"></iframe>
			</div>
			<div id="getin">
				<a href="<?php echo htmlSpecialChars($control->link("changeState", array('signup'))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Shows form for creating new account.')) ?>
" class="ajax" id="signup_button"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Sign Up'), ENT_NOQUOTES) ?></a>
				<a href="<?php echo htmlSpecialChars($control->link("changeState", array('login'))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Shows form for login.')) ?>
" class="ajax" id="login_button"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Log in'), ENT_NOQUOTES) ?></a>
			</div>
			<div class="clear"></div>
			<a href="<?php echo htmlSpecialChars($control->link("changeState", array('facebook'))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Use your facebook account to login or sign up.')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Login with Facebook'), ENT_NOQUOTES) ?></a> |
			<a href="<?php echo htmlSpecialChars($control->link("changeState", array('twitter'))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Use your twitter account to login or sign up.')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Login with Twitter'), ENT_NOQUOTES) ?></a> |
			<a href="<?php echo htmlSpecialChars($control->link("changeState", array('forgotten'))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Change your forgotten password.')) ?>
" class=""><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Forgotten password'), ENT_NOQUOTES) ?></a>
<?php endif ?>
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
<div id="<?php echo $control->getSnippetId('introductionBlock') ?>"><?php call_user_func(reset($_l->blocks['_introductionBlock']), $_l, $template->getParams()) ?>
</div><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
