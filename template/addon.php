<div class="addon">
<?php
$user = new user;
$levelCom = $user->levelCommon;
unset($user);
if ($this->owner->level > $levelCom) {
?>
<h3 class="change"><a href="moderator.php?action=addonForm&amp;addon=<?php echo $addon->id ?>" title="<?php echo $lng->change ?>"><?php echo $lng->change ?></a></h3>
<?php
}
?>
<?php echo $addon->content ?>
<hr class="clear" />
</div>