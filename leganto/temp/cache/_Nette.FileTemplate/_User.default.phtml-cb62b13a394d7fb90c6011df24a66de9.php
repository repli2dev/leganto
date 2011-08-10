<?php //netteCache[01]000405a:2:{s:4:"time";s:21:"0.94506200 1312984867";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:83:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/User/default.phtml";i:2;i:1312959570;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/templates/User/default.phtml

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'l1o662ig0k')
;//
// block subcontent
//
if (!function_exists($_l->blocks['subcontent'][] = '_lbe3dfde64d5_subcontent')) { function _lbe3dfde64d5_subcontent($_l, $_args) { extract($_args)
?>
    <div id="user-info">
		<h1><?php echo Nette\Templating\DefaultHelpers::escapeHtml($user->nickname, ENT_NOQUOTES) ?></h1>
<?php if ($user->about != NULL): ?>
			<div class="about">
				<?php echo $template->texySafe($user->about) ?>

			</div>
<?php endif ?>
		<table class="stats <?php echo htmlSpecialChars($user->role) ?>" cellspacing="0">
<?php if ($user->role != 'common'): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("System role"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml(System::translate(ucfirst($user->role)), ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if ($user->birthyear != NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Birth year"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($user->birthyear, ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if ($user->sex != NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Sex"), ENT_NOQUOTES) ?></th>
					<td><?php if ($user->sex == "female"): echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Female"), ENT_NOQUOTES) ;else: echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Male"), ENT_NOQUOTES) ;endif ?></td>
				</tr>
<?php endif ;if ($user->lastLogged !== NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Last logged"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->date($user->lastLogged), ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if ($achievement !== NULL && $achievement->booksTotal !== NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Read books"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($achievement->booksTotal, ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if ($achievement !== NULL && $achievement->opinionsTotal !== NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Opinions"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($achievement->opinionsTotal, ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if ($achievement !== NULL && $achievement->postsTotal !== NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Discussion posts"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($achievement->postsTotal, ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if ($achievement !== NULL && $achievement->followersTotal !== NULL): ?>
				<tr>
					<th><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Followers"), ENT_NOQUOTES) ?></th>
					<td><?php echo Nette\Templating\DefaultHelpers::escapeHtml($achievement->followersTotal, ENT_NOQUOTES) ?></td>
				</tr>
<?php endif ;if (System::isCurrentlyLogged($user->getId()) && $achievement !== NULL && $achievement->opinions == 2 && $user->role == 'common'): ?>
			<tr class="to-be-moderator">
				<td colspan="2">
					<?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate('If you add %s opinions you will be a moderator.', (50-$achievement->opinionsTotal > 0 ? 50-$achievement->opinionsTotal : 0)), ENT_NOQUOTES) ?>

				</td>
			</tr>
<?php endif ?>
		</table>
        <img class="icon" src="<?php echo htmlSpecialChars($template->userico($user->getId(),150)) ?>
" alt="<?php echo htmlSpecialChars($user->nickname) ?>" />
<?php if ($achievement !== NULL): ?>
			<div class="achievements">
				<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($achievement->books, "books")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate("level based on number of books read by the user")) ?>
" alt="<?php echo htmlSpecialChars($template->translate("level based on the number of books read by the user")) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>img/achievement_books_<?php echo htmlSpecialChars($achievement->books) ?>.png" />
				<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($achievement->opinions, "opinions")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate("level based on number of user's opinions")) ?>
" alt="<?php echo htmlSpecialChars($template->translate("level based on number of user's opinions")) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>img/achievement_opinions_<?php echo htmlSpecialChars($achievement->opinions) ?>.png" />
				<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($achievement->posts, "posts")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate("level based on number of user's discussion posts")) ?>
" alt="<?php echo htmlSpecialChars($template->translate("level based on number of user's discussion posts")) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>img/achievement_posts_<?php echo htmlSpecialChars($achievement->posts) ?>.png" />
				<img class="achievement" title="<?php echo htmlSpecialChars($template->achievementName($achievement->followers, "followers")) ?>
 &ndash; <?php echo htmlSpecialChars($template->translate("level based on number of user's followers")) ?>
" alt="<?php echo htmlSpecialChars($template->translate("level based on number of user's followers")) ?>
" src="<?php echo htmlSpecialChars($baseUri) ?>img/achievement_followers_<?php echo htmlSpecialChars($achievement->followers) ?>.png" />
			</div>
<?php endif ?>
		
		<div class="clear">&nbsp;</div>
	</div>
	<div class="clear">&nbsp;</div>
	<h2><?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Opinions"), ENT_NOQUOTES) ?></h2>
	@<?php $_ctrl = $control->getWidget("opinionList"); if ($_ctrl instanceof Nette\Application\UI\IPartiallyRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>


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
?>
@<?php $_l->extends = layout.phtml ?>


@<?php 
// template extending support
if ($_l->extends) {
	ob_end_clean();
	Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render();
}
