<form action="<?php echo $action ?>" method="get">
	<fieldset>
		<legend><?php echo $lng->search ?></legend>
		<p><input name="searchValue" type="text" /><label><?php echo $lng->searchValue ?></label>
		<p>
			<select name="action">
				<option value="bookSearch"><?php echo $lng->book ?></option>
				<option value="writerSearch"><?php echo $lng->writer ?></option>
				<option value="tagSearch"><?php echo $lng->tag ?></option>
			</select>
			<label><?php echo $lng->searchItem ?></label>
		</p>
		<p><input type="submit" value="<?php echo $lng->search ?>"></p>
	</fieldset>
</form>