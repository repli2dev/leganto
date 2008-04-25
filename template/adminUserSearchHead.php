<thead>
	<td><a href="admin.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=id" title="<?php echo $lng->order ?>"><?php echo $lng->id ?></td>
	<td><a href="admin.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=name" title="<?php echo $lng->order ?>"><?php echo $lng->name ?></td>
	<td><a href="admin.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=level" title="<?php echo $lng->order ?>"><?php echo $lng->level ?></td>
	<td><?php echo $lng->sendEmail ?></td>
	<td><a href="admin.php?action=<?php echo $_GET["action"] ?>&amp;name=<?php echo $_GET["name"] ?>&amp;order=recommend" title="<?php echo $lng->order ?>"><?php echo $lng->carma ?></td>
	<td><?php echo $lng->ban ?></td>
</thead>