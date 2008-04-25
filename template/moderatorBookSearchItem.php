<form action="<?php echo $action ?>" method="post">
	<tr>
		<td>
			<?php echo $book->id ?> 
 		</td>
 		<td>
 			<input type="hidden" name="book" value="<?php echo $book->id ?>" />
  			<input type="text" name="bookTitle" value="<?php echo $book->title ?>" />
 		</td>
 		<td>
  			<input type="text" name="writerName" value="<?php echo $book->writerName ?>" />
 		</td>
 		<td>
  			<a href="search.php?column=bookTitle&amp;searchWord=<?php echo $book->title ?>"><?php echo $book->readed ?></a>
 		</td>
 		<td>
			<input type="submit" value="<?php echo $lng->change ?>" /> 		
 		</td>
	</tr>
</form>