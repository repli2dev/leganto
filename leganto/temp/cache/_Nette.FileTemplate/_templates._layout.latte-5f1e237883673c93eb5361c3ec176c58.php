<?php //netteCache[01]000400a:2:{s:4:"time";s:21:"0.72491300 1313510745";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:78:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/@layout.latte";i:2;i:1313510740;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/@layout.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '9l50evx67w')
;//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb1cba67dafe_content')) { function _lb1cba67dafe_content($_l, $_args) { extract($_args)
?>
			<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('No content'), ENT_NOQUOTES) ?>

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php if (isset($pageDescription)): ?>
		<meta name="description" content="<?php echo htmlSpecialChars($pageDescription) ?>" />
<?php endif ;if (isset($pageKeywords)): ?>
		<meta name="keywords" content="<?php echo htmlSpecialChars($pageKeywords) ?>" />
<?php endif ?>
		<meta name="author" content="Jan Papoušek, Jan Drábek <?php echo htmlSpecialChars($template->translate('and our community, altogether.')) ?>" />
<?php if (isSet($robots) && $robots): ?>
			<meta name="robots" content="index, follow" />
<?php else: ?>
			<meta name="robots" content="noindex, nofollow" />
<?php endif ?>
		<meta property="fb:app_id" content="175434332634" />

				<link rel="alternate" type="application/rss+xml" title="Leganto: feed of all users" href="<?php echo htmlSpecialChars($presenter->link("Rss:")) ?>" />
<?php if ($presenter->getUser()->isLoggedIn()): ?>
			<link rel="alternate" type="application/rss+xml" title="Leganto: your feed" href="<?php echo htmlSpecialChars($presenter->link("Rss:", array($presenter->getUser()->getId()))) ?>" />
<?php endif ?>
		
		<link rel="stylesheet" type="text/css" href="/css/main.css" />
		<link rel="stylesheet" type="text/css" href="/css/theme.css" />
		<link rel="stylesheet" type="text/css" href="/css/forms.css" />
		<link rel="stylesheet" type="text/css" href="/css/flashes.css" />
		<link rel="stylesheet" type="text/css" href="/css/lists.css" />

		<script type="text/javascript" src="/js/netteForms.js"></script>
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/search.js"></script>
<?php try { $presenter->link("Search:*"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($presenter->getLastCreatedRequestFlag("current")): ?>
			<script type="text/javascript">
			$(function(){
				// Unset all others
				$('#search label').each(function() {
					$(this).attr("id", "");
				});
				// Triger the correct one
				labelId = $("#search input[value=<?php echo $presenter->getAction() ?>]").attr("id");
				$("#search label[for="+labelId+"]").attr("id","selected-search-option");
			});
			</script>
<?php endif ?>
		<link rel="stylesheet" type="text/css" href="/css/jquery.ui.autocomplete.css" />
		<script type="text/javascript" src="/js/jquery.external.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.8.2.custom.min.js"></script>
<?php try { $presenter->link("Book:*"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($presenter->getLastCreatedRequestFlag("current")): ?>
			<script type="text/javascript" src="/js/jquery.ui.stars.min.js"></script>
			<link rel="stylesheet" type="text/css" href="/css/jquery.ui.stars.min.css" />
			<script type="text/javascript" src="/js/ratings.js"></script>
<?php endif ?>
		<script type="text/javascript" src="/js/jquery.nette.js"></script>
		<script type="text/javascript" src="/js/jquery.ajaxform.js"></script>
		<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="/js/jquery.autoresize.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('textarea').autoResize({});
			});
		</script>
		<script type="text/javascript" src="/js/jquery.unhide.js"></script>

<?php try { $presenter->link("Default:default"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($presenter->getLastCreatedRequestFlag("current")): ?>
			<script type="text/javascript" src="/js/jquery.nivo.slider.pack.js"></script>
			<link rel="stylesheet" type="text/css" href="/css/nivo-slider.css" />
			<script type="text/javascript">
				$(window).load(function() {
					$('.slider').nivoSlider({
						 effect:'fade',
						 pauseTime:6000,
						 captionOpacity:1,
						 directionNav:false
					});
				});
			</script>
<?php endif ?>

		<!--[if IE]>
		<script type="text/javascript" src="/js/corner.js"></script>
		<script type="text/javascript">
			DD_roundies.addRule('#content', '10px');
			DD_roundies.addRule('#book-stats h3, #mentioned-yes, #mentioned-no, .help-category, .list .item,.error,.flash', '5px');
			DD_roundies.addRule('#cover .rating', '0px 0px 5px 0px');
			DD_roundies.addRule('#cover .stats', '5px 0px 0px 0px');
		</script>
		<![endif]-->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-2881824-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

		<link rel="image_src" href="<?php echo htmlSpecialChars($baseUrl) ?>img/logoForFB.png" />
		<link rel="shortcut icon" href="/favicon.ico" />

		<title><?php if ($pageTitle): echo Nette\Templating\DefaultHelpers::escapeHtml($pageTitle, ENT_NOQUOTES) ?>
 &ndash; <?php endif ?>Leganto<?php try { $presenter->link("Default:default"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($presenter->getLastCreatedRequestFlag("current")): ?>
 &ndash; <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("readers"), ENT_NOQUOTES) ;endif ?></title>
	</head>
	<body>
		<div id="header">
			<div class="wrapper">
				<a href="/" title="Leganto &ndash; <?php echo htmlSpecialChars($template->translate("readers")) ?>" id="logo">
					<span id="page-name">Leganto</span>
					<span id="motto" title="<?php echo htmlSpecialChars($template->translate('Our sisifos effort ;-)')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Man shall not live by bread alone'), ENT_NOQUOTES) ?></span>
				</a>
<?php $_ctrl = $control->getWidget("search"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
				<div class="clear"></div>
			</div>
		</div>

<?php $_ctrl = $control->getWidget("navigation"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>

		<div id="main">
			<div class="wrapper">
<?php if (!$_l->extends) { call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()); } ?>
				<div class="clear"></div>
			</div>
		</div>

		<div id="footer">
			<div class="wrapper">
				&copy; <a href="<?php echo htmlSpecialChars($baseUri) ?>" title="Leganto - <?php echo htmlSpecialChars($template->translate('Internet reading lists')) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($domain->uri, ENT_NOQUOTES) ?>
</a> - <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Internet reading lists'), ENT_NOQUOTES) ?>
 - <a href="<?php echo htmlSpecialChars($control->link("Help:text", array(56))) ?>
" title="<?php echo htmlSpecialChars($template->translate('Terms of use')) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Terms of use'), ENT_NOQUOTES) ?></a>
				|
<?php $email = $domain->email ?>
				<a href="mailto:<?php echo htmlSpecialChars($email) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Contact us'), ENT_NOQUOTES) ?></a>
				|
				<a href="<?php echo htmlSpecialChars($presenter->link("Statistics:")) ?>"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Statistics'), ENT_NOQUOTES) ?></a>
				<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('created by'), ENT_NOQUOTES) ?>

				<a href="http://jandrabek.cz" title="Jan Drábek">Jan Drábek</a>
				&amp;
				<a href="mailto:jan.papousek@gmail.com" title="Jan Papoušek">Jan Papoušek</a>
				<span title="Richard Šefr, Tereza Doležalová"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('and others'), ENT_NOQUOTES) ?></span>.
			</div>
		</div>
	</body>
</html><?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
