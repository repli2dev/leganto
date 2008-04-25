<?php
require("./include/config.php");
$temp->header($lng->discussion);
$temp->menu();
switch($_GET[action]) {
 case "addDiscuss":
  $dis = new discussion;
  $dis->create($_POST[discussText]);
 break;
 case "destroyDis":
  $dis = new discussion;
  $dis->destroyByID($_GET[dis]);
 break;
}
$temp->discussionRead($_GET[page]);
$temp->middle();
$temp->tagListTop();
$temp->footer();
?>