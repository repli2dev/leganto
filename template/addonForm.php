<form action="<?php echo $action ?>" method="post">
	<fieldset>
		<legend><?php if ($addonID) { echo $lng->changeAddon; } else { echo $lng->addAddon; } ?></legend>
		<p>
			<input type="text" name="book" value="<?php if ($addonID) { echo $addon->book; } else { echo $bookID; } ?>" /><label><?php echo "$lng->book - $lng->id" ?></label>		
		</p>
		<p>
			<textarea name="content"><?php echo $addon->content ?></textarea>
		</p>
		<p>
<?php
if($addonID) {
 $url = "moderator.php?action=addonDestroy&amp;addon=$addonID";
?>
			<a href="<?php echo $url ?>" title="<?php echo $lng->delete ?>"><button><?php echo $lng->delete ?></button></a>
<?php
}
?>
			<input type="submit" value="<?php if ($addonID) { echo $lng->changeAddon; } else { echo $lng->addAddon; } ?>" class="submit" />					
		</p>
	</fieldset>
</form>