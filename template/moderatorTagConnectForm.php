<form action="<?php echo $action ?>" method="post">
	<fieldset>
		<legend><?php echo $lng->tagConnect ?></legend>
		<p>
			<input type="text" name="startTag" /><label><?php echo "$lng->startTag - $lng->id" ?></label>
		</p><p>
			<input type="text" name="finishTag" /><label><?php echo "$lng->finishTag - $lng->id" ?></label>
		</p><p>
			<input type="submit" value="<?php echo $lng->tagConnect ?>" />
		</p>	
	</fieldset>
</form>