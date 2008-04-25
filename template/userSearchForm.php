<form action="<?php echo $action ?>" method="get">
	<fieldset>
		<legend><?php echo $lng->userSearch ?></legend>
		<p><input name="name" type="text" /><label><?php echo $lng->userName ?></label>
		<p><input name="action" type="submit" value="<?php echo $lng->search ?>"></p>
	</fieldset>
</form>