<form action="<?php echo $action ?>" method="post">
	<fieldset>
		<legend><?php echo $lng->bookEdit ?></legend>
		<input type="hidden" name="book" value="<?php echo $book->id ?>" />
		<p>
			<input type="text" name="bookTitle" value="<?php echo $book->title ?>" />		
		</p>
		<p>
			<input type="submit" value="<?php echo $lng->edit ?>" />
		</p>
	</fieldset>
</form>