<table id="tagListMod" rules="groups">
 <thead>
  <td>
	<a href="<?php echo $url."id"; if ($_GET[order] == "id") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->id ?></a>
  </td>  
  <td>
   <a href="<?php echo $url."name"; if ($_GET[order] == "name") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->tag ?></a>
  </td>
  <td>
	<a href="<?php echo $url."tagged"; if ($_GET[order] == "tagged") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->tagged ?></a>
  </td>
  <td>
	<?php echo $lng->change ?>  
  </td>
 </thead>