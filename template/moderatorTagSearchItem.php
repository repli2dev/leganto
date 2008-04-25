<form action="<?php echo $action ?>" method="post">
	<tr>
 		<td>
  			<?php echo $tag->id ?> 
 		</td>
 		<td>
 			<input type="hidden" name="tag" value="<?php echo $tag->id ?>" />
  			<input type="text" name="tagName" value="<?php echo $tag->name ?>" />
 		</td>
 		<td>
 			<a href="search.php?column=tag&amp;searchWord=<?php echo $tag->name ?>"><?php echo $tag->tagged ?></a>
 		</td>
 		<td>
 			<input type="submit" value="<?php echo $lng->change ?>">
 		</td>
	</tr>
</form>