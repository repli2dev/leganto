<?php //netteCache[01]000413a:2:{s:4:"time";s:21:"0.78035000 1313673222";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:91:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/connections.latte";i:2;i:1313516177;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/Settings/connections.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'hurgvgk77g')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lb63e2113a7c_subcontent')) { function _lb63e2113a7c_subcontent($_l, $_args) { extract($_args)
?>
	<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Social networks'), ENT_NOQUOTES) ?></h1>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Here you can see and manage connections with social networks.'), ENT_NOQUOTES) ?></p>
	<p><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Add new social network'), ENT_NOQUOTES) ?>:</p>
	<ul class="social-networks">
		<li class="facebook"><?php if (isSet($used['facebook'])): echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Facebook'), ENT_NOQUOTES) ?>
 | <small><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('You have already connected to this network.'), ENT_NOQUOTES) ?>
</small><?php else: ?><a href="<?php echo htmlSpecialChars($control->link("facebook")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Facebook'), ENT_NOQUOTES) ?>
</a><?php endif ?></li>
		<li class="twitter"><?php if (isSet($used['twitter'])): echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Twitter'), ENT_NOQUOTES) ?>
 | <small><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('You have already connected to this network.'), ENT_NOQUOTES) ?>
</small><?php else: ?><a href="<?php echo htmlSpecialChars($control->link("twitter")) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Twitter'), ENT_NOQUOTES) ?>
</a><?php endif ?></li>
	</ul>
<?php if (count($data) > 0): ?>
		<table class="connections-list">
			<thead>
				<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('ID'), ENT_NOQUOTES) ?></th>
				<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Service/website'), ENT_NOQUOTES) ?></th>
				<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Action'), ENT_NOQUOTES) ?></th>
			</thead>
			<tbody>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data) as $row): ?>
					<tr>
						<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row->id_connection, ENT_NOQUOTES) ?></td>
						<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row->type, ENT_NOQUOTES) ?></td>
						<td><a class="delete" href="<?php echo htmlSpecialChars($control->link("delete", array($row->id_connection))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Delete'), ENT_NOQUOTES) ?></a></td>
					</tr>
<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
			</tbody>
		</table>
<?php else: ?>
		<p><em><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('Your account is not connected with any account on social networks.'), ENT_NOQUOTES) ?></em></p>
<?php endif ?>
	
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
