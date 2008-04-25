<table id="bookListMod" rules="groups">
 <thead>
  <td>
	<a href="<?php echo $url."id"; if ($_GET[order] == "id") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->id ?></a>
  </td>  
  <td>
   <a href="<?php echo $url."title"; if ($_GET[order] == "title") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->bookTitle ?></a>
  </td>
  <td>
   <a href="<?php echo $url."writerName"; if ($_GET[order] == "writerName") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->writer ?></a>
  </td>
  <td>
   <a href="<?php echo $url."readed"; if ($_GET[order] == "readed") { echo " DESC"; } ?>" title="<?php echo $lng->order ?>"><?php echo $lng->readed ?></a>
  </td>
  <td>
	<?php echo $lng->change ?>
  </td>
 </thead>