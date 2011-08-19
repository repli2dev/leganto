<?php //netteCache[01]000419a:2:{s:4:"time";s:21:"0.19963100 1313673250";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:97:"/home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/FollowedUser/FollowedUser.latte";i:2;i:1312991062;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"6889b94 released on 2011-08-04";}}}?><?php

// source file: /home/Weby/Ostatni/preader/www/leganto/app/FrontModule/components/FollowedUser/FollowedUser.latte

?><?php list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'nujn1jbkqc')
;
// snippets support
if (!empty($control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($control, $_l, get_defined_vars());
}

//
// main template
//
?>
<p>
<?php if (isSet($data["opinion"])): ?> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Book read"), ENT_NOQUOTES) ?>
: <?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data["opinion"]) as $row): ?>
<a href="<?php echo htmlSpecialChars($presenter->link("User:", array($row[0]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row[1], ENT_NOQUOTES) ?>
</a><?php if (!$iterator->isLast()): ?>, <?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
<br /><?php endif ?>

<?php if (isSet($data["wanted"])): ?> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Want read"), ENT_NOQUOTES) ?>
: <?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data["wanted"]) as $row): ?>
<a href="<?php echo htmlSpecialChars($presenter->link("User:", array($row[0]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row[1], ENT_NOQUOTES) ?>
</a><?php if (!$iterator->isLast()): ?>, <?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
<br /><?php endif ?>

<?php if (isSet($data["reading"])): ?> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Are reading just now"), ENT_NOQUOTES) ?>
: <?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data["reading"]) as $row): ?>
<a href="<?php echo htmlSpecialChars($presenter->link("User:", array($row[0]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row[1], ENT_NOQUOTES) ?>
</a><?php if (!$iterator->isLast()): ?>, <?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
<br /><?php endif ?>

<?php if (isSet($data["owned"])): ?> <?php echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("Owns"), ENT_NOQUOTES) ?>
: <?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data["owned"]) as $row): ?>
<a href="<?php echo htmlSpecialChars($presenter->link("User:", array($row[0]))) ?>
"><?php echo Nette\Templating\DefaultHelpers::escapeHtml($row[1], ENT_NOQUOTES) ?>
</a><?php if (!$iterator->isLast()): ?>, <?php endif ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ?>
<br /><?php endif ?>

<?php if (empty($data)): echo Nette\Templating\DefaultHelpers::escapeHtml($template->translate("No follower has anything in common with this book."), ENT_NOQUOTES) ;endif ?>

</p>