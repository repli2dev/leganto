<?php
require("./include/config.php");
$temp->header($lng->moderator);
$temp->menu();
$moderator = new moderator;

switch($_GET["action"]) {
 default:
  $temp->moderatorBookSearch("","",0);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
  $temp->moderatorBookConnectForm();
 break;
 case "addonChange":
  $moderator->addonChange($_GET[addon],$_POST[content],$_POST[book]);
  $temp->bookInfo($_POST[book]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();   
 break;
 case "addonCreate":
  $moderator->addonCreate($_POST[book],$_POST[content]);
  $temp->bookInfo($_POST[book]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();  
 break;
 case "addonDestroy":
  $temp->moderatorBookSearch("","",0);
  $moderator->addonDestroy($_GET[addon]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm(); 
 break;
 case "addonForm":
  $temp->addonForm($_GET[book],$_GET[addon],$_GET[book]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
 break;
 case "bookConnect":
  $moderator->bookConnect($_POST[startBook],$_POST[finishBook]);
  $temp->moderatorBookSearch("","",0);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
  $temp->moderatorBookConnectForm();
 break;
 case "bookEdit":
  $moderator->bookEdit($_POST[book],$_POST[bookTitle],$_POST[writerName]);
  $temp->moderatorBookSearch($_POST[bookTitle],"",0);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
 break;
 case "bookSearch":
  $temp->moderatorBookSearch($_GET[searchValue],$_GET[order],$_GET[page]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
  $temp->moderatorBookConnectForm();
 break;
 case "tagConnect":
  $moderator->tagConnect($_POST[startTag],$_POST[finishTag]);
  $temp->moderatorTagSearch("","",0);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
  $temp->moderatorTagConnectForm();
 break;
 case "tagEdit":
  $moderator->tagEdit($_POST[tag],$_POST[tagName]);
  $temp->moderatorTagSearch($_POST[tagName],"","");
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
 break;
 case "tagSearch":
  $temp->moderatorTagSearch($_GET[searchValue],$_GET[order],$_GET[page]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();  
  $temp->moderatorTagConnectForm();
 break;
 case "writerEdit":
  $moderator->writerEdit($_POST[writer],$_POST[writerName]);
  $temp->moderatorWriterSearch($_POST[writerName],"","");
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
 break;
 case "writerSearch":
  $temp->moderatorWriterSearch($_GET[searchValue],$_GET[order],$_GET[page]);
  $temp->middle();
  $temp->moderatorMenu();
  $temp->moderatorSearchForm();
  //$temp->moderatorWriterConnectForm(); 
 break;
}

$temp->footer();
?>