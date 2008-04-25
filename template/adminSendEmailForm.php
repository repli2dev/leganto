<form action=<?php echo $action ?>&amp;user=<?php echo $_GET[user] ?>" method="post">
	<fieldset>
		<legend><?php echo $lng->sendEmail ?></legend>
		<p>
			<input type="text" value="<?php echo $user->name ?>" disabled /><label><?php echo $lng->userName ?></label>		
		</p>
		<p>
			<input type="text" name="subject" /><label><?php echo $lng->subject ?></label>		
		</p>
		<p>
			<textarea name="content"></textarea>		
		</p>
		<p>
			<input type="submit" value="<?php echo $lng->sendEmail ?>" />		
		</p>	
	</fieldset>
</form>