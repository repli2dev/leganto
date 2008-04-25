<?php if ($op->content) {?>
<strong>
<a href="user.php?user=<?php echo $op->userID ?>&amp;opinion=<?php echo $op->id ?>&amp;action=opinion" title="<?php echo $op->userName." - ".$op->rating."*" ?>">
	<?php echo $op->userName ?>
</a>
</strong>
<?php } else { ?>
<a href="user.php?user=<?php echo $op->userID ?>&amp;opinion=<?php echo $op->id ?>&amp;action=opinion" title="<?php echo $op->userName." - ".$op->rating."*" ?>">
	<?php echo $op->userName ?>
</a>
<?php } ?>