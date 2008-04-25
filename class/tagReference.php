<?php
class tagReference extends reader {
 public $table;

 public function __construct() {
  parent::__construct();
  $this->table = $this->sqlPrefix."tagReference";
 }

 public function create($tag,$book) {
  if ($tag) {
   $trans = array(", " => ",");
   $tag = strtr($tag,$trans);
   $tag = explode(",",$tag);
   $tagClass = new tag;
   foreach($tag as $tagName) {
    $tagID = $tagClass->create($tagName);
    $sql = "SELECT id FROM $this->table WHERE book = $book AND tag = $tagID";
    $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
    $record = mysql_fetch_object($res);
    if (!$record->id) {
     $sql = "INSERT INTO $this->table VALUES(
      0,
      $tagID,
      $book
     )";
     mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
    }
   }
  }
 }
 
 public function connect($startTag,$finishTag) {
  $sql = "UPDATE $this->table SET tag = $finishTag WHERE tag = $startTag";
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  $tag = new tag;
  $tag->destroy($startTag);
  unset($tag);
 } 
 
 public function install() {
  $tag = new tag;
  $book = new book;
  $sql = "CREATE TABLE $this->table (
   id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   tag INT(25),
   book INT(25),
   FOREIGN KEY (tag) REFERENCES $tag->table (id),
   FOREIGN KEY (book) REFERENCES $book->table (id)
  )";
  unset($tag);
  unset($book);
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 }
}
?>
