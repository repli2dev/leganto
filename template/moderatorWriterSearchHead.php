<table rules="groups">
	<thead>
		<td>
			<a href="<?php echo $url."id"; if ($_GET[order] == "id") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->id ?></a>		
		</td>
		<td>
			<a href="<?php echo $url."name"; if ($_GET[order] == "name") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->writer ?></a>
		</td>
		<td>
			<a href="<?php echo $url."countBook"; if ($_GET[order] == "countBook") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->bookCount ?></a>	
		</td>
		<td>
			<?php echo $lng->edit ?>		
		</td>
	</thead>