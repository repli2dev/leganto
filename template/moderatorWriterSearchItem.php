<form action="<?php echo $action ?>" method="post">
	<tr>
 		<td>
  			<?php echo $writer->id ?> 
 		</td>
 		<td>
 			<input type="hidden" name="writer" value="<?php echo $writer->id ?>" />
  			<input type="text" name="writerName" value="<?php echo $writer->name ?>" />
 		</td>
 		<td>
 			<a href="search.php?column=writer&amp;searchWord=<?php echo $writer->name ?>"><?php echo $writer->countBook ?></a>
 		</td>
 		<td>
 			<input type="submit" value="<?php echo $lng->change ?>">
 		</td>
	</tr>
</form>