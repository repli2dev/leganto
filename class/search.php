<?php
class search extends reader {
 public $limit; 
 
 public function __construct() {
  parent::__construct();
  $this->limit = 40;
 } 

 private function conByAll($value) {
  $condition = "(".$this->conByBook($value).") OR (".$this->conByWriter($value).") OR (".$this->conByTag($value).")";
  return $condition; 
 }
 
 private function conByBook($value) {
  $book = new book;
  $condition = "";
  foreach($value AS $item) {
   if ($condition) $condition .= " AND ";
   $condition .= "$book->table.asciiTitle LIKE '%$item%'";  
  }  
  unset($book);
  return $condition;
 } 
 
 private function conByTag($value) {
  $tag = new tag; $tagReference = new tagReference; $book = new book;	
  foreach ($value as $item) {
   if ($condition) $condition .= " AND ";
   $condition .= "$book->table.id IN (
    SELECT $tagReference->table.book
    FROM $tagReference->table
    LEFT JOIN $tag->table ON $tagReference->table.tag = $tag->table.id
    WHERE $tag->table.asciiName LIKE '%$item%'
   )";  
  }
  unset($tag);
  unset($tagReference);
  return $condition;
 }
 
 private function conByWriter($value) {
  $list = $this->cut($this->utf2ascii($writer));
  $writer = new writer;
  $condition = "";
  foreach($value AS $item) {
   if ($condition) $condition .= " AND ";
   $condition .= "$writer->table.asciiName LIKE '%$item%'";  
  }
  unset($writer);
  return $condition;
 }
 
 private function makeQuestion($condition,$order,$page) {
  $book = new book;
  $comment = new comment;
  $writer = new writer;
  $opinion = new opinion;
  $order = "ORDER BY $order";
  $limit = $page*$this->limit;
  $sql = "
   SELECT
    $book->table.id,
    $book->table.title AS title,
    $book->table.asciiTitle,
    $book->table.date AS date,
    $writer->table.id AS writerID,
    $writer->table.name AS writerName,
    COUNT($comment->table.id) AS commentCount,
    ROUND(AVG($opinion->table.rating)) as rating,
    (SELECT COUNT($opinion->table.id) FROM $opinion->table WHERE $opinion->table.book = $book->table.id) AS readed,
    (
     SELECT COUNT($book->table.id)
     FROM $book->table
     INNER JOIN $writer->table ON $book->table.writer = $writer->table.id
  	  WHERE $condition
    ) AS pageHelp,
   $this->limit AS pageLimit
   FROM $book->table
   INNER JOIN $writer->table ON $book->table.writer = $writer->table.id
   LEFT	 JOIN $comment->table ON $book->table.id = $comment->table.book
   INNER JOIN $opinion->table ON $book->table.id = $opinion->table.book
   WHERE
   $condition
   GROUP BY $book->table.id
   $order
   LIMIT $limit,$this->limit
  ";
  return $sql;
  unset($book);
  unset($comment);
  unset($writer);
  unset($opinion);
  unset($tag);
  unset($tagReference);
 }
 
 public function search($type,$value,$order,$page) {
  $value = $this->cut($this->utf2ascii($value));
  switch ($order) {
   case "rating": $order .= " DESC"; break;
   case "readed": $order .=  " DESC"; break;
   case "": $order = "readed DESC";
  } 
  if (!$page) $page = 0; 
  switch ($type) {
   case "bookTitle": $condition = $this->conByBook($value);
   break;
   case "writer": $condition = $this->conByWriter($value);
   break;
   case "tag": $condition = $this->conByTag($value);
   break;
   case "all": $condition = $this->conByAll($value);
   break;
  }
  $sql = $this->makeQuestion($condition,$order,$page);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }
 
 public function url($text) {
  $change = array("," => "%2C", " " => "+");
  return strtr($text, $change);
 }
}
?>