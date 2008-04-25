<tr>
	<td>
		<?php echo $user->id ?>	
	</td><td>
		<a href="user.php?user=<?php echo $user->id ?>" title="<?php echo $user->name ?>"><?php echo $user->name ?></a>	
	</td><td>
		<?php echo $user->level ?>	
	</td><td>
		<a href="admin.php?action=sendEmailForm&amp;user=<?php echo $user->id ?>" title="<?php echo $lng->sendEmail ?>"><?php echo $lng->sendEmail ?></a>
	</td><td>
		<?php echo $user->recommend ?>
	</td><td>
		<a href="admin.php?action=ban&amp;user=<?php echo $user->id ?>" title="<?php echo $lng->ban ?>"><?php echo $lng->ban ?></a>	
	</td>
</tr>