<?php
if (!$isFavourite) {
?>
<h3><a href="book.php?book=<?php echo $_GET[book] ?>&amp;action=makeCommentFavourite" title="<?php echo $lng->makeCommentFavourite ?>"><?php echo $lng->makeCommentFavourite ?></a></h3>
<?php
} else {
?>
<h3><a href="book.php?book=<?php echo $_GET[book] ?>&amp;action=unMakeCommentFavourite" title="<?php echo $lng->unMakeCommentFavourite ?>"><?php echo $lng->unMakeCommentFavourite ?></a></h3>
<?php
}
?>