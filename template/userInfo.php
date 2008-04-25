<?php
if ($this->owner->id != $user->id) {
$recommend = new recommend;
?>
<div id="user">
<?php
if (($this->owner->id) and ($isMine = $recommend->isMine($user->id))) {
?>
		<h3 class="destroyFavourite"><a href="<?php echo $urlDestroyFavourite ?>" title="<?php echo $lng->destroyFavourite ?>"><?php echo $lng->destroyFavourite ?></a></h3>
<?php
} elseif($this->owner->id) {
?>
		<h3 class="makeFavourite"><a href="<?php echo $urlMakeFavourite ?>" title="<?php echo $lng->makeFavourite ?>"><?php echo $lng->makeFavourite ?></a></h3>
<?php }?>
		<img src="./ico/<?php echo $user->id ?>.jpg" alt="<?php echo $user->name ?>" class="ico" />
		<h1><a href="user.php?user=<?php echo $user->id ?>" title="<?php echo $user->name ?>"><?php echo $user->name ?></a></h1>
		<h4>
<?php
 if ($this->owner->id) {
  echo $lng->similirity.": ";
  $usSim = new userSim;
  echo $usSim->get($user->id);
  echo "%";
  unset($usSim);
 }
?>
		</h4>
		<h4><?php echo $lng->carma ?>: <?php echo $user->recommend ?> | <a href="rss.php?action=user&amp;user=<?php echo $user->id ?>" title="<?php echo $user->name ?> - rss" >rss</a></h4>
		<hr class="clear" />
		<div class="description">
			<?php echo $user->description ?>
		</div>
</div>
<?php
}
?>