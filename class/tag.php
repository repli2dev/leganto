<?php
class tag extends reader {
 public $table;
 public $limit;
 
 public function __construct() {
  parent::__construct();
  $this->table = $this->sqlPrefix."tag";
  $this->limit = 50;
 }	
 
 public function change($id,$tag) {
  $asciiTag = $this->utf2ascii($tag);
  $sql = "UPDATE $this->table SET name = '$tag',asciiName = '$asciiTag' WHERE id = $id";
  mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 } 
 
 public function create($name) {
  $id = $this->getID($name);
  $asciiName = $this->utf2ascii(strToLower($name));
  if (!$id) {
   $sql = "INSERT INTO $this->table VALUES(0,'$name','$asciiName')";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  }
  return $this->getID($name);
 }
 
 public function destroy($id) {
  $sql = "DELETE FROM $this->table WHERE id = $id";  mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 }
 
 public function getByBook($bookID) {
  $tagReference = new tagReference;
  $sql = "
   SELECT
    $tagReference->table.id AS refID,
    $this->table.id,
    $this->table.name,
    ROUND((
     (SELECT COUNT(id) AS thisCount FROM $tagReference->table WHERE tag = $this->table.id)/
     (SELECT COUNT(id) AS tagCount FROM $tagReference->table)
    )*20) AS size
   FROM $tagReference->table
   LEFT JOIN $this->table ON $tagReference->table.tag = $this->table.id
   WHERE $tagReference->table.book = $bookID
   GROUP BY $this->table.id
   ORDER BY $this->table.name
  ";
  unset($tagReference);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }
 
 public function getListTop($limit = 60) { 
  $tagReference = new tagReference;
  $sql = "
   SELECT
    $this->table.id     
   FROM $this->table
   ORDER BY
    (SELECT COUNT(id) AS thisCount FROM $tagReference->table WHERE tag = $this->table.id)
   DESC
   LIMIT 0,$limit
  ";   
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $help = "";
  while ($record = mysql_fetch_object($res)) {
   if ($help) $help .= ", ";
   $help .= $record->id;
  }
  $sql = "
   SELECT
    $this->table.id,
    $this->table.name,
    ROUND((
     (SELECT COUNT(id) AS thisCount FROM $tagReference->table WHERE tag = $this->table.id)/
     (SELECT COUNT(id) AS tagCount FROM $tagReference->table)
    )*30) AS size
   FROM $this->table
   LEFT JOIN $tagReference->table ON $this->table.id = $tagReference->table.tag
   WHERE
    $this->table.id IN ($help) 
   GROUP BY $this->table.id
   ORDER BY $this->table.name    
  ";
  unset($tagReference); 
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 } 
 
 public function getID($name) {
  $asciiName = $this->utf2ascii(strToLower($name));
  $sql = "SELECT id FROM $this->table WHERE asciiName = '$asciiName'";
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  return $record->id;
 
 }
 
 public function getName($id) {
  $sql = "SELECT name FROM $this->table WHERE id = $id";
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_array($res);
  return $record->name;
 }
 
 public function install() {
  $sql = "CREATE TABLE $this->table (
   id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   name VARCHAR(64) UNIQUE NOT NULL,
   aciiName VARCHAR(64) NOT NULL
  )";
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 }
 
 public function search($tagValue,$order,$page) {
  switch($order) {
   default: $order = "ORDER BY $order"; break;
   case "tagged": $order = "ORDER BY $order DESC"; break;
   case "": $order = "ORDER BY name";
  }
  $limit = $page*$this->limit;
  $tagValue = $this->cut($this->utf2ascii($tagValue));
  $tag = new tag;
  $tagReference = new tagReference;
  $condition = "";
  foreach ($tagValue as $item) {
   if ($condition) $condition .= " OR ";
   $condition .= "$this->table.asciiName LIKE '%$item%'";  
  }
  $sql = "
  	SELECT
  	 $this->table.id AS id,
    $this->table.name AS name,
    COUNT($tagReference->table.id) AS tagged,
    (SELECT COUNT(id) FROM $this->table WHERE $condition) AS pageHelp,
    $this->limit AS pageLimit
   FROM $this->table
   LEFT JOIN $tagReference->table ON $this->table.id = $tagReference->table.tag
   WHERE $condition
   GROUP BY $this->table.id
   $order
   LIMIT $limit,$this->limit";
  unset($tag);
  unset($tagReference);
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }
}
?>