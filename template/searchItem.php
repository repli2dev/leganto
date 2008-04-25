<tr>
 <td>
  <a href="book.php?book=<?php echo $book->id ?>" title="<?php echo $book->title ?>"><?php echo $book->title ?>
 </td>
 <td>
  <a href="search.php?searchWord=<?php echo $urlWriter ?>&amp;column=writer" title="<?php echo $book->writerName ?>"><?php echo $book->writerName ?>
 </td>
 <td>
  <img src="<?php echo $this->imageRating($book->rating) ?>" />
 </td>
 <td>
  <?php echo $book->readed ?>
 </td>
</tr>