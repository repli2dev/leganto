 <tr>
 	<td>	
 		<a href="book.php?book=<?php echo $book->id ?>" title="<?php echo $book->title ?>"><?php echo $book->title ?></a>
	</td><td>   
   	<a href="search.php?searchWord=<?php echo $urlWriter ?>&amp;column=writer" title="<?php echo $book->writerName ?>"><?php echo $book->writerName ?></a>
	</td><td>	
		<img src="<?php echo $this->imageRating($book->rating) ?>" class="rating" />
	</td>
</tr>
