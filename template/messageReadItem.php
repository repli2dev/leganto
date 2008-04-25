<div class="message">	
 <div class="content">
  <?php echo $message->content ?>
 </div>
 <div class="info">
  <a class="author" href="user.php?user=<?php echo $message->userIDFrom ?>" title="<?php echo $message->userNameFrom ?>"><?php echo $message->userNameFrom ?></a>
  <span class="arrow">&raquo;</span>
  <a class="author" href="user.php?user=<?php echo $message->userIDTo ?>" title="<?php echo $message->userNameTo ?>"><?php echo $message->userNameTo ?></a>
  <span class="date"><?php echo $message->date ?></span>
  <a class="admin" href="message.php?action=destroy&amp;suggest=yes&amp;message=<?php echo $message->mesID ?>" ><?php echo $lng->delete ?></a>
 </div>
 <hr class="clear" />
</div>
