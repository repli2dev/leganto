<?php
class opinion extends reader {
 public $table;
 
 public function __construct() {
  parent::__construct();
  $this->table = $this->sqlPrefix."opinion";
 }

 public function change($opID,$rating,$content) {
  $sql = "
   UPDATE $this->table SET
   rating = $rating,
   content = '$content'
   WHERE id = $opID
  ";
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 }

 public function create($bookTitle,$writerNameFirst,$writerNameSecond,$opinionText,$rating,$tag) {
  $writerName = "$writerNameSecond $writerNameFirst";
  $lng = new language;
  try {
   if (!$bookTitle) throw new error($lng->withoutBookTitle);
   if (!$writerNameFirst or !$writerNameSecond) throw new error($lng->withoutWriterName);
   if (!$tag) throw new error($lng->withoutTag);
   if ($opinionText == $lng->opinion) $opinionText = "";
   $book = new book;
   $bookHelp = $book->create($bookTitle,$writerName,$image);
   $tagClass = new tagReference;
   $tagClass->create($tag,$bookHelp->id);
   unset($tag);
   $userID = $this->owner->id;
   $sql = "SELECT id FROM $this->table WHERE user = $userID AND book = $bookHelp->id ";
   $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
   $help = mysql_fetch_object($res);
   if ($help->id) throw new error($lng->opinionExists);
   $sql = "INSERT INTO $this->table VALUES(
    0,
    $userID,
    $bookHelp->id,
    '$opinionText',
    $rating,
    now()
   )";
   unset($book);
   mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);
 }

 public function getInfoByID($opID) {
  $book = new book;
  $writer = new writer;  
  $sql = "
   SELECT
    $this->table.id,
    $this->table.user AS userID,
    $this->table.content,
    $this->table.rating,
    $this->table.date,
    $book->table.id AS bookID,
    $book->table.title AS bookTitle,
    $writer->table.id AS writerID,
    $writer->table.name AS writerName
   FROM $this->table
   INNER JOIN $book->table ON $this->table.book = $book->table.id
   INNER JOIN $writer->table ON $book->table.writer = $writer->table.id
   WHERE $this->table.id = $opID
   GROUP BY $this->table.id
  ";
  unset($book);
  unset($writer);	
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  return $record;
 }

 public function getListByBook($book) {
  $user = new user;
  $sql = "
   SELECT
    $this->table.id,   
    $this->table.rating,
    $this->table.content,
    $user->table.id AS userID,
    $user->table.name AS userName
   FROM $this->table
   INNER JOIN $user->table ON $this->table.user = $user->table.id
   WHERE $this->table.book = $book
   GROUP BY $this->table.id   
   ORDER BY $user->table.name
  ";
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 } 
 
 public function install() {
  $user = new user;
  $book = new book;
  $sql = "CREATE TABLE $this->table (
   id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   user INT(25),
   book INT(25),
   content TEXT,
   rating ENUM ('1','2','3','4','5') NOT NULL,
   date DATETIME default '0000-00-00 00:00:00'
   FOREIGN KEY (user) REFERENCES $user->table (id),
   FOREIGN KEY (book) REFERENCES $book->table (id)
  )";
  unset($user);
  unset($book);
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 }
 
 public function isMine($bookID) {
  $owner = $this->owner->id;
  $sql = "SELECT id FROM $this->table WHERE book = $bookID AND user = $owner";
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  if (mysql_num_rows($res) > 0) return TRUE;
  else return FALSE;
 }
}
?>
