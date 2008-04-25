<?php
class recommend extends reader {
 
 public function __construct() {
  parent::__construct();
  $this->table = $this->sqlPrefix."recommend";
 }

 public function create($user) {
  $lng = new language;
  try {
   if (!$this->owner->id) throw new error($lng->accessDenied);
	$owner = $this->owner->id;   
   $sql = "SELECT id FROM $this->table WHERE user = $owner AND recommend = $user";
   $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $record = mysql_fetch_object($res);
   if ((!$record->id) and ($user != $owner)) {
    $sql = "INSERT INTO $this->table VALUES(0,$owner,$user);";
    mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   }
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);
 }

 public function destroyMine($usID) {
  $this->destroy($usID,$this->owner->id); 
 }

 public function destroy($usID,$ownerID) {
  $sql = "DELETE FROM $this->table WHERE user = $ownerID AND recommend = $usID";
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 } 
 
 public function getInfoByItem($item,$value) {
  $sql = "SELECT * FROM $this->table WHRE $item = '$value'";
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  return $record;
 }

 public function install() {
  $user = new user;
  $sql = "CREATE TABLE $this->table (
   id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   user INT(25),
   recommend INT(25),
   FOREIGN KEY (user) REFERENCES $user->table (id),
   FOREIGN KEY (recommend) REFERENCES $user->table (id)
  )";
  unset($user);
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql); 
 }
 
 public function isMine($usID) {
  $owner = $this->owner->id;
  $sql = "SELECT id FROM $this->table WHERE user = $owner AND recommend = $usID";
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  if ($record->id) return true;
  else return false;
 } 
 
 public function byUser($userID) {
  $user = new user;
  $sql = "
	SELECT
    $user->table.id,
    $user->table.name,
    $user->table.password,
    $user->table.email,
    $user->table.description,
    $user->table.level,
    $user->table.login,
    COUNT($this->table.id) AS recommend
	FROM $this->table
	INNER JOIN $user->table ON $this->table.recommend = $user->table.id
	WHERE $this->table.user = $userID
	GROUP BY $user->table.id
	ORDER BY name
  ";
  unset($user);
  $res = mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }
}
?>
