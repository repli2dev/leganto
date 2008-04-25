<thead>
	<td><a href="user.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=name" title="<?php echo $lng->order ?>"><?php echo $lng->name ?></td>
	<td><a href="user.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=login" title="<?php echo $lng->order ?>"><?php echo $lng->lastLogged ?></td>
	<td><a href="user.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=recommend" title="<?php echo $lng->order ?>"><?php echo $lng->carma ?></td>
	<td><a href="user.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=similirity" title="<?php echo $lng->order ?>"><?php echo $lng->similirity ?></td>
</thead>