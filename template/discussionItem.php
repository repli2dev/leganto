<div class="discussionItem">	
 <div class="content">
  <?php echo $dis->text ?>
 </div>
 <div class="info">
  <a class="author" href="user.php?user=<?php echo $dis->userID ?>" title="<?php echo $dis->userName ?>"><?php echo $dis->userName ?></a>
  <span class="date"><?php echo $dis->date ?></span>
  <?php if (($this->owner->level == $user->levelModerator) or ($this->owner->level == $user->levelAdmin) or ($this->owner->id == $dis->userID)) { ?>
  <a class="admin" href="discussion.php?action=destroyDis&amp;dis=<?php echo $dis->id ?>&amp;page=<?php echo $_GET["page"] ?>" ><?php echo $lng->delete ?></a>
  <?php } ?>
 </div>
 <hr class="clear" />
</div>
