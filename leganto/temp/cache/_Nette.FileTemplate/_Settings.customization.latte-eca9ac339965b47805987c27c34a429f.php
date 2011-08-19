<?php //netteCache[01]000415a:2:{s:4:"time";s:21:"0.16829400 1313673224";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:93:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/customization.latte";i:2;i:1313516246;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/customization.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'j72f1pfbwg')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbd9523f4376_subcontent')) { function _lbd9523f4376_subcontent($_l, $_args) { extract($_args)
?>
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Customization'), ENT_NOQUOTES) ?></h1>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Here you prepare block with read books for your personal page.'), ENT_NOQUOTES) ?>
 <a href="<?php echo htmlSpecialChars($presenter->link("Help:text", array(61))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("More information"), ENT_NOQUOTES) ?></a></p>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Copy the text below into your webpage:"), ENT_NOQUOTES) ?>

	<pre>
&lt;div id="leganto-read-list"&gt;
   &lt;div class="leganto-logo"&gt;&lt;a href="http://www.leganto.cz"&gt;
      &lt;img width="36px" height="37px" src="http://www.leganto.cz/img/logo.png" alt="Leganto &ndash Čtenáři" /&gt;
      &lt;span class="leganto-name"&gt;Leganto&lt;/span&gt;&lt;/a&gt;&nbsp;
   &lt;/div&gt;
   &lt;div id="leganto-content"&gt;
      &lt;em&gt;Žádné přečtené knihy&lt;/em&gt;
   &lt;/div&gt;
&lt;/div&gt;
&lt;link rel="stylesheet" type="text/css" href="http://www.leganto.cz/css/read-books.css" /&gt;
&lt;script type="text/javascript" src="http://www.leganto.cz/js/read-books.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="http://www.leganto.cz/user/last-opinions?user=<?php echo $user->getId() ?>&empty=0&limit=3&callback=legantoFillData"&gt;&lt;/script&gt;
	</pre>
<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = true; unset($_extends, $template->_extends);


if ($_l->extends) {
	ob_start();
} elseif (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
$_l->extends = "layout.latte" ?>

<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
