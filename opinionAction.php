<?php
require("./include/config.php");
switch ($_GET["action"]) {
 case "addForm":
  $temp->header($lng->addBook);
  $temp->menu();
  $temp->addBookForm($_GET[book]);
  $temp->middle();
  $temp->tagListTop();
  $temp->footer();
 break;
 case "add":
  $user = new user;  
  $userInfo = $user->getInfo($temp->owner->id);
  unset($user);
  $temp->header("$userInfo->name");
  $temp->menu();
  
  $opinion = new opinion;
  $opinion->create($_POST["title"],$_POST["writerNameFirst"],$_POST["writerNameSecond"],$_POST["opinion"],$_POST["rating"],$_POST["tag"]);

  $temp->topBookByUser($temp->owner->id);
  $temp->newBookByUser($temp->owner->id);
  $temp->allUserBookLink();
  $temp->middle();
  $temp->userInfo($temp->owner->id);
  $temp->userFavourite($temp->owner->id);
  $temp->bookByFavourite($temp->owner->id);
  $temp->footer();
 break;
 case "changeForm":
  $temp->header($lng->addBook);
  $temp->menu();
  $temp->opinionChangeForm($_GET["opinion"]);
  $temp->middle();
  $temp->tagListTop();
  $temp->footer();
 break;
 case "change":
  $user = new user;  
  $userInfo = $user->getInfo($temp->owner->id);
  unset($user);
  $temp->header("$userInfo->name");
  $temp->menu();
  $opinion = new opinion;
  $opinion->change($_GET["opinion"],$_POST["rating"],$_POST["opinion"]);
  $op = $opinion->getInfoByID($_GET["opinion"]);
  unset($opinion);
  $temp->opinion($op->id);
  $temp->middle();
  $temp->userInfo($op->usID);
  $temp->footer();  
 break;
}
?>
