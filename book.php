<?php
require("./include/config.php");

$bookClass = new book;
$book = $bookClass->getInfo($_GET["book"]);
unset($bookClass);

switch ($_GET["action"]) {
 default:
  $temp->header($book->title);
  $temp->menu();
  $temp->bookInfo($_GET[book]);
  $temp->middle();
  $temp->searchSimilarityLink($_GET[book]);
  $temp->addBookLink($_GET[book]);
  $temp->opinionList($book->id);
  $temp->footer();
 break;  
 case "addTag":
  $tagRef = new tagReference;
  $tagRef->create($_POST["tagName"],$_GET["book"]);
  $temp->header($book->title);
  $temp->menu();
  $temp->bookInfo($_GET[book]);
  $temp->middle();
  $temp->searchSimilarityLink($_GET[book]);
  $temp->addBookLink($_GET[book]);
  $temp->opinionList($book->id);
  $temp->footer();
 break;
 case "addComment":
  $temp->header($book->title);
  $temp->menu();
  $comment = new comment;
  $comment->create($_GET["book"],$_POST["comText"]);
  unset($comment);
  $temp->bookInfo($_GET[book]);
  $temp->middle();
  $temp->opinionList($book->id);
  $temp->footer();
 break;
 case "destroyComment":
  $comment = new comment;
  $comment->destroyByID($_GET["com"]);
  $temp->header($book->title);
  $temp->menu();
  $temp->bookInfo($_GET[book]);
  $temp->middle();
  $temp->opinionList($book->id);
  $temp->footer();
 break;
 case "makeCommentFavourite":
  $comFav = new comFavourite;
  $comFav->create($_GET[book]);
  unset($comFav);
  $temp->header($book->title);
  $temp->menu();
  $temp->bookInfo($_GET[book]);
  $temp->middle();
  $temp->searchSimilarityLink($_GET[book]);
  $temp->opinionList($book->id);
  $temp->footer();
 break;
 case "similarity":
  $temp->header("$book->title - $lng->similarBooks");
  $temp->menu();
  $temp->searchSimilirity($_GET["book"],$_GET["order"],$_GET["page"]);
  $temp->middle();
  $temp->footer();
 break;
 case "unMakeCommentFavourite":
  $comFav = new comFavourite;
  $comFav->destroy($_GET[book]);
  unset($comFav);
  $temp->header($book->title);
  $temp->menu();
  $temp->bookInfo($_GET[book]);
  $temp->middle();
  $temp->searchSimilarityLink($_GET[book]);
  $temp->addBookLink($_GET[book]);
  $temp->opinionList($book->id);
  $temp->footer();
 break; 
}
?>
