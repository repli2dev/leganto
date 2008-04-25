<form action="<?php echo $action ?>" method="post">
	<fieldset>
		<legend><?php echo $lng->bookConnect ?></legend>
		<p>
			<input type="text" name="startBook" /><label><?php echo "$lng->bookStart - $lng->id" ?></label>
		</p><p>
			<input type="text" name="finishBook" /><label><?php echo "$lng->bookFinish - $lng->id" ?></label>
		</p><p>
			<input type="submit" value="<?php echo $lng->bookConnect ?>" />
		</p>	
	</fieldset>
</form>