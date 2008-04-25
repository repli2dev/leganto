<div class="comment" id="com<?php echo $number ?>">	
 <div class="content">
  <?php echo $comment->text ?>
 </div>
 <div class="info">
  <a href="#com<?php echo $number ?>" class="number">[<?php echo $number ?>]</a>
  <a class="author" href="user.php?user=<?php echo $comment->userID ?>" title="<?php echo $comment->userName ?>"><?php echo $comment->userName ?></a>
  <span class="date"><?php echo $comment->date ?></span>
  <?php if (($this->owner->level == $user->levelModerator) or ($this->owner->level == $user->levelAdmin) or ($this->owner->id == $comment->userID)) { ?>
  <a class="admin" href="book.php?action=destroyComment&amp;com=<?php echo $comment->id ?>&amp;book=<?php echo $_GET["book"] ?>" ><?php echo $lng->delete ?></a>
  <?php } ?>
 </div>
 <hr class="clear" />
</div>
