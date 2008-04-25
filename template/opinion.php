<div id="opinion">
	<h2>
		<a href="book.php?book=<?php echo $op->bookID ?>" title="<?php echo $op->bookTitle ?>">
			<?php echo $op->bookTitle ?>
		</a>
		<img src="<?php echo $this->imageRating($op->rating) ?>" class="rating" />	
	</h2>
	<h3>
	 <a href="search.php?searchWord=<?php echo $urlWriter ?>&amp;column=writer" title="<?php echo $op->writerName ?>">
	 	<?php echo $op->writerName ?>
	 </a>
	</h3>
<?php
$this->tagListByBook($op->bookID);
if ($this->owner->id) $this->addTagForm($op->bookID);
?>
	<div class="content">
<?php  if ($this->owner->id == $op->userID) { ?>
		<h3 class="change"><a href="opinionAction.php?action=changeForm&amp;opinion=<?php echo $op->id ?>" title="<?php echo $lng->change ?>"><?php echo $lng->change ?></a></h3>
<?php } ?>
		<h3><?php echo $user->name.":" ?></h3>
		<?php echo $op->content ?>
	</div>
</div>