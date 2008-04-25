<div>	
	<form id="login" action="<?php echo $action ?>" method="post">
		<fieldset>
			<p><input type="text" name="name" /><label><?php echo $lng->name ?></label></p>
  	   	<p><input type="password" name="password" /><label><?php echo $lng->pswd ?></label></p>
  	 		<p><input type="submit" value="<?php echo $lng->logIn ?>" class="loginSubmit" /><a href="user.php?action=regForm" title="<?php echo $lng->registrate ?>"><?php echo $lng->registrate ?></a></p>
		</fieldset>
	</form>
</div>