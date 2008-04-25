<div id="user">
	<img src="./ico/<?php echo $this->owner->id ?>.jpg" alt="<?php echo $this->owner->name ?>" class="ico" />
	<h1>
		<a href="user.php?user=<?php echo $this->owner->id ?>" title="<?php echo $this->owner->name ?>"><?php echo $this->owner->name ?></a>
	</h1>
 	<h4><?php echo $lng->carma ?>: <?php echo $this->owner->recommend ?> | <a href="rss.php?action=user&amp;user=<?php echo $this->owner->id ?>" title="<?php echo $this->owner->name ?> - rss" >rss</a></h4>
<?php
if ((basename($_SERVER[SCRIPT_NAME]) == "user.php") and (($_GET[user] == $this->owner->id)  or ($_POST[name] == $this->owner->name))) {
?>
	<div class="description">
<?php
$texy = new texy;
$texy->utf = true;
echo $texy->process($this->owner->description);
?>
	</div>
<?php
}
?>	
	<p><a href="user.php?action=changeForm" title="<?php echo $lng->changeUserInfo ?>"><?php echo $lng->changeUserInfo ?></a> | <a href="user.php?action=out" title="<?php echo $lng->logOut ?>"><?php echo $lng->logOut ?></a></p>
</div>
<?php
if ((basename($_SERVER[SCRIPT_NAME]) == "user.php") and (($_GET[user] == $this->owner->id)  or ($_POST[name] == $this->owner->name))) {
	if (($_GET[user]) and ($_GET[user] != $this->owner->id)) {
		$user = new user;
		$user = $user->getInfo($_GET[user]);
		$this->messageSendLink($user->name);
	}
	else {
		$this->messageSendLink();
	}
}
?>