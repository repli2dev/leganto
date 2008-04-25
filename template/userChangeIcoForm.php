<form action="<?php echo $action ?>" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend><?php echo $lng->changeIco ?></legend>	
    	<p>
    		<input type="file" name="ico" id="ico" />
    		<label><?php echo $lng->ico ?></label>
    	</p><p>
			<input type="submit" value="<?php echo $lng->changeIco ?>" />    	
    	</p>
	</fieldset>
</form>