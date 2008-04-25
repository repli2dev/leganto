<tr>
 <td>
  <a href="user.php?user=<?php echo $_GET["user"] ?>&amp;opinion=<?php echo $book->opinionID ?>&amp;action=opinion" title="<?php echo $book->title ?>"><?php echo $book->title ?>
 </td>
 <td>
  <a href="search.php?searchWord=<?php echo $urlWriter ?>&amp;column=writer" title="<?php echo $book->writerName ?>"><?php echo $book->writerName ?>
 </td>
 <td>
  <img src="<?php echo $this->imageRating($book->rating) ?>" />
 </td>
</tr>