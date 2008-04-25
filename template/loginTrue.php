<div>
	<h1>
		<a href="user.php?user=<?php echo $this->owner->id ?>" title="<?php echo $this->owner->name ?>"><?php echo $this->owner->name ?></a>
	</h1>
	<p><a href="userAction.php?action=changeForm" title="<?php echo $lng->changeUserInfo ?>"><?php echo $lng->changeUserInfo ?></a> | <a href="user.php?action=out" title="<?php echo $lng->logOut ?>"><?php echo $lng->logOut ?></a></p>
</div>