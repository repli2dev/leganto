<div id="page">
<?php if (!($backward < 0)) { ?>
 <a href="<?php echo $url."&amp;page=".$backward ?>" title="<?php $lng->backward ?>" id="backward"><?php echo $lng->backward ?></a>
<?php } if (!($pageMax<$forward)) {?>	
 <a href="<?php echo $url."&amp;page=".$forward ?>" title="<?php $lng->forward ?>" id="forward"><?php echo $lng->forward ?></a>
<?php } ?>
</div>
<hr class="clear" />