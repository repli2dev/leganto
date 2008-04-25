<?php
require("./include/config.php");

$user = new user;
$userInfo = $user->getInfo($_GET["user"]);

switch ($_GET["action"]) {
 case "bookAll":
  $temp->header("$userInfo->name");
  $temp->menu();
  $temp->bookByUser($_GET["user"],$_GET["order"],$_GET["page"]);
  $temp->middle();
  $temp->userInfo($_GET["user"]);
  $temp->userFavourite($_GET["user"]);
  $temp->footer();
 break;
 case "list":
  $temp->header($lng->userList);
  $temp->menu();
  $temp->userListAll($_GET["order"],$_GET["page"]);
  $temp->middle();
  $temp->userSearchForm();
  $temp->footer();
 break;
 case "destroyFavourite": {
  $temp->header("$userInfo->name");
  $temp->menu();
  $recommend = new recommend;
  $recommend->destroyMine($_GET["user"]);
  $temp->topBookByUser($_GET["user"]);
  $temp->newBookByUser($_GET["user"]);
  $temp->middle();
  $temp->userInfo($_GET["user"]);
  $temp->footer();
 }
 break;
 case "makeFavourite": {
  $temp->header("$userInfo->name");
  $temp->menu();
  $recommend = new recommend;
  $recommend->create($_GET["user"]);
  $temp->topBookByUser($_GET["user"]);
  $temp->newBookByUser($_GET["user"]);
  $temp->middle();
  $temp->userInfo($_GET["user"]);
  $temp->footer();
 }
 break;
 case "opinion":
  $opinion = new opinion;
  $opInfo = $opinion->getInfoByID($_GET["opinion"]);
  $temp->header("$userInfo->name - $opInfo->bookTitle");
  $temp->menu();
  $temp->opinion($_GET["opinion"]);
  $temp->middle();
  $temp->searchSimilarityLink($opInfo->bookID);
  $temp->userInfo($_GET["user"]);
  $temp->footer();  
 break;
 case $lng->search:
  $temp->header("$lng->userSearch - ".$_GET["name"]);
  $temp->menu();
  $temp->userListAll($_GET["order"],$_GET["page"],$_GET["name"]);
  $temp->middle();
  $temp->userSearchForm();
  $temp->footer();   
 break;
 case "in":
  $temp->header($_POST["name"]);
  $user->logIn($_POST["name"],$_POST["password"]);
  $temp->owner = $_SESSION["logUser"];
  $_GET[user] = $temp->owner->id;
  if ($temp->owner->id) {
   $temp->menu();   
   $temp->topBookByUser($temp->owner->id);
   $temp->newBookByUser($temp->owner->id);
   $temp->allUserBookLink($temp->owner->id);
   $temp->middle();
   $temp->userInfo($temp->owner->id);
   $temp->comNotRead($_GET["user"]);
   $temp->userFavourite($temp->owner->id);
   $temp->bookByFavourite($_GET[user]);
  }
  $temp->footer();  
 break;
 case "out":
  session_destroy();
  $diversion = "./index.php";
  Header("Location: $diversion");  
 break;
 case "changeForm":
  $temp->header($lng->userChangeInfo);
  $temp->menu();
  $temp->userChangeMeForm();
  $temp->middle();
  $temp->userInfo($temp->owner->id);
  $temp->tagListTop();
  $temp->footer();
 break;
 case "change":
  $temp->header($lng->userChangeInfo);
  $temp->menu();
  $user->changeMeAll($_POST["email"],$_POST["description"],$_POST["pswd"],$_POST["pswdCtrl"]);
  $temp->userChangeMeForm();
  $temp->middle();
  $temp->userInfo($temp->owner->id);
  $temp->tagListTop();
  $temp->footer();
 break;
 case "regForm":
  $temp->header($lng->registration);
  $temp->menu();
  $temp->registrationForm();
  $temp->middle();
  $temp->tagListTop();
  $temp->footer();
 break;
 case "create":
  $temp->header($lng->registration);
  $user = new user;
  $user->create($_POST["name"],$_POST["pswd"],$_POST["pswdCtrl"],$_POST["email"],$_POST["description"]);
  unset($user);
  $temp = new template;
  $temp->menu();
  if ($temp->owner->id) $temp->message($lng->userCreate);
  $temp->middle();
  $temp->tagListTop();
  $temp->footer();
 break;
 default:
  $temp->header("$userInfo->name");
  $temp->menu();
  $temp->topBookByUser($_GET["user"]);
  $temp->newBookByUser($_GET["user"]);
  $temp->allUserBookLink();
  $temp->middle();
  $temp->userInfo($_GET["user"]);
  $temp->comNotRead($_GET["user"]);
  $temp->userFavourite($_GET["user"]);
  $temp->bookByFavourite($_GET[user]);
  $temp->footer();
 break;
}


?>