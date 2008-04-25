<?php
class writer extends reader {
 public $table;
 public $limit = 50;

 public function __construct() {
  parent::__construct();
  $this->table = $this->sqlPrefix."writer";
 }
 
 public function create($name) {
  $writerID = $this->getDuplicity($name);
  if (!$writerID) {
   $asciiName = $this->utf2ascii($name);
   $asciiName = strToLower($asciiName);
   $sql = "INSERT INTO $this->table VALUES(0,'$name','$asciiName')";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $writerID = mysql_insert_id();
  }
  return $writerID;
 }
 
 public function change($id,$name) {
  try {
   $sql = "SELECT id FROM $this->table WHERE name = '$name'";
   $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $record = mysql_fetch_object($res);
   if (($record->id) and ($record->id != $id)) {
    $lng = new language;
    throw new error($lng->writerExists);
   }
   $asciiWriterName = $this->utf2ascii($name);
   $sql = "UPDATE $this->table SET name = '$name', asciiName = '$asciiWriterName' WHERE id = $id";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  }
  catch(error $e) {
   $e->scream();
   $this->connect($id,$record->id);
  }
 } 
 
 public function connect($start,$finish) {
  $book = new book;
  $sql = "UPDATE $book->table SET writer = $finish WHERE writer = $start";
  unset($book);
  mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $sql = "DELETE FROM $this->table WHERE id = $start";
  mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 }
 
 public function getDuplicity($writer) {
  $writer = $this->utf2ascii($writer);
  $writerList = explode(" ",$writer);
  $condition = "WHERE ";
  $help = false;
  foreach($writerList as $writerItem) {
   if ($help) $condition .= " AND ";
  	$condition .= "asciiName LIKE '%$writerItem%'";
  	$help = true;
  }
  $sql = "SELECT id FROM $this->table $condition";
  $res = mysql_query($sql);
  $record = mysql_fetch_object($res);
  return $record->id;
 } 
 
 public function getInfoByItem($item,$value) {
  $book = new book;
  $sql = "
   SELECT
    $this->table.id,
    $this->table.name,
    $this->table.asciiName,
    COUNT($book->table.id) AS countBook
   FROM $this->table
   LEFT JOIN $book->table ON $this->table.id = $book->table.writer
   WHERE $this->table.$item = '$value'
   GROUP BY $this->table.id
  ";
  unset($book);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  return $record; 
 }

 public function getAll() {
  $sql = "SELECT name FROM $this->table";
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }

 public function getNameFirst($name) {
  $name = explode(" ",$name);
  $help = 0;
  $first = "";
  foreach ($name AS $item) {
   if ($help) {
    if ($help > 1) { $first .= " "; }
    $help++;
    $first .= $item;
   } else {
    $help = 1;
   }
  }
  return $first;
 } 
 
 public function getNameSecond($name) {
  $second = explode(" ",$name);
  return $second[0]; 
 }
 
 public function install() {
  $sql = "CREATE TABLE $this->table (
   id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   name VARCHAR(250) NOT NULL,
   asciiName VARCHAR(250) NOT NULL FULLTEXT
  )";
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql); 
 }
 
 public function search($name,$order,$page) {
  switch($order) {
   default: $order = "ORDER BY $order"; break;
   case "": $order = "ORDER BY name";
  }
  $limit = $page*$this->limit;
  $name = $this->cut($this->utf2ascii($name));
  foreach($name AS $item) {
   if ($condition) $condition .= " AND ";
   $condition .= "$this->table.asciiName LIKE '%$item%'";  
  }
  $book = new book;
  $sql = "
	SELECT
		$this->table.id,
		$this->table.name,
		COUNT($book->table.id) countBook,
		(SELECT COUNT(id) FROM $this->table WHERE $condition) AS pageHelp,
		$this->limit AS pageLimit
	FROM
		$this->table
	LEFT JOIN $book->table ON $this->table.id = $book->table.writer
	WHERE $condition
	GROUP BY $this->table.id
	$order
	LIMIT $limit,$this->limit
  ";
  unset($book);
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }
}
?>
