<form action="<?php echo $action ?>" method="post" id="sendMessage" >
	<fieldset>
		<legend><?php echo $lng->sendMessage ?></legend>
		<p><input type="text" name="userName" value="<?php echo $userName ?>" id="userName" onKeyUp="AutoSuggest.CreateResults(event);" /><label><?php echo $lng->userName ?></label></p>
		<select id="SuggestResultsUserName" multiple>
	   </select>
<script language="JavaScript">
	window.AutoSuggest = new AutoSuggestBox('userName', 'SuggestResultsUserName', GetResultsUserName, HandleChoice);
</script>	   
		<p><textarea name="mesText"></textarea></p>
		<p><input type="submit" value="<?php echo $lng->sendMessage ?>" /></p>		
	</fieldset>
</form>