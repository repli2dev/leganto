<form action="<?php echo $action ?>" method="get">
	<fieldset>
		<legend><?php echo $lng->bookSearch ?></legend>
		<p><input name="title" type="text" /><label><?php echo $lng->bookTitle ?></label>
		<p><input name="action" type="submit" value="<?php echo $lng->search ?>"></p>
	</fieldset>
</form>