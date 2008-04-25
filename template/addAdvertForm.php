<form action="<?php echo $action ?>" method="post">
	<fieldset>
		<legend><?php echo $lng->addAdvert ?></legend>
		<p>
			<input type="text" name="book" /><label><?php echo "$lng->book - $lng->id" ?></label>		
		</p>
		<p>
			<input type="text" name="endDate" /><label><?php echo $lng->date ?></label>	
		</p>
		<p>
			<textarea name="content"></textarea>
		</p>
		<p>
			<input type="submit" value="<?php echo $lng->addAdvert ?>" class="submit" />		
		</p>
	</fieldset>
</form>