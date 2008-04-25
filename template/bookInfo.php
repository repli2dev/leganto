<div id="bookInfo">
	<h1>
		<?php echo $book->title ?>
		<img src="<?php echo $this->imageRating($book->rating) ?>" class="rating" />
	</h1>
	<h2><a href="search.php?searchWord=<?php echo $urlWriter ?>&amp;column=writer" title="<?php echo $book->writerName ?>"><?php echo $book->writerName ?></a></h2> 
   <hr class="clear" />
