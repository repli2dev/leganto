<?php
class user extends reader {
 public $levelAdmin;
 public $levelBan;
 public $levelCommon;
 public $levelModerator;
 public $limit;
 public $table;

 public function __construct() {
  parent::__construct();
  $this->levelAdmin = 4;
  $this->levelBan = 1;
  $this->levelCommon = 2;
  $this->levelModerator = 3;
  $this->limit = 50;
  $this->table = $this->sqlPrefix."user";
 }

 public function ban($userID) {
  $lng = new language;
  try {
   if ($this->owner->level != $this->levelAdmin) throw new error($lng->accessDenied);
   $sql = "SELECT level FROM $this->table WHERE id = $userID";
   $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $us = mysql_fetch_object($res);
   if ($us->level == $this->levelBan) $level = $this->levelCommon;
   else $level = $this->levelBan;
   $sql = "UPDATE $this->table SET level = $level WHERE id = $userID";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);
 }

 public function changeAll($id,$email,$description,$pswd = NULL,$pswdCtrl = NULL,$level = NULL) {
  $lng = new language;
  try {
   $user = $this->getFullInfo($id);
   if (($this->owner->level != $this->levelAdmin) and ($this->owner->id =! $id)) throw new error($lng->accessDenied);
   if ($this->owner->level != $this->levelAdmin) $level = $this->levelCommon;
   if ($pswd) {
    if ($pswd != $pswdCtrl) throw new error($lng->noPasswordAgreement);
    $pswd = sha1($pswd);
   }
   else $pswd = $user->password;
   $sql = "UPDATE $this->table SET
    email = '$email',
    level = $level,
    description = '$description',
    password = '$pswd'
    WHERE id = $id    
   ";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);
 }
 
 public function changeIco($image) {
  try {  
   $owner = $this->owner->id;
   move_uploaded_file($image[tmp_name],"./".$image[name]);
   $maxHeight = 80;
   $maxWidth = 80;
   $cfgExtensions = array('jpg','jpeg');
   $path = PathInfo($image[name]);
   $lng = new language;
   if (!In_Array(StrToLower($path['extension']), $cfgExtensions)) throw new error($lng->wrongImgType);
   $img = getImageSize($image[name]);
   $width = $img[0]; $height = $img[1];   
   if ($img[0] > $maxWidth) {
    $height = ($maxWidth/$img[0])*$img[1];
    $width = $maxWidth;
   }
   if ($img[1] > $maxHeight) {
    $width = ($maxHeight/$img[1])*$img[0];
    $height = $maxHeight;   
   }
   $out = ImageCreateTrueColor($width,$height);
   $source = ImageCreateFromJpeg($image[name]);
   unlink($image[name])	;
   ImageCopyResized ($out, $source,0,0,0,0,$width,$height,$img[0],$img[1]);
   ImageJpeg ($out, "./$owner.jpg", 50);
   ImageDestroy($out);
   ImageDestroy($source);
  }
  catch (error $exception) {
   $exception->scream();
  }
 } 
 
 public function changeMeAll($email,$description,$pswd = NULL,$pswdCtrl = NULL) {
  $this->changeAll($this->owner->id,$email,$description,$pswd,$pswdCtrl,$this->owner->level); 
 }

 public function create($name,$password,$password2,$email,$description,$level = 2) {
  $lng = new language;
  try {
   if (!$name) throw new error($lng->withoutName);
   if (!$password) throw new error($lng->withoutPassword);
   if (!$email) throw new error($lng->withoutEmail);
   if (!$level) throw new error( __FILE__." : ".__LINE__);
   if ($password != $password2) throw new error($lng->withoutPasswordAgreement);
   $asciiName = $this->simpleName($name);
   $sql = "SELECT id FROM $this->table WHERE asciiName = '$asciiName'";
   $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $record = mysql_fetch_object($res);
   if ($record->id) throw new error($lng->userExists);
   $sql = "INSERT INTO $this->table VALUES(
    0,
    '$name',
    '$asciiName',
    '".sha1($password)."',
    '$email',
    '$description',
    $level,
    now()
   )";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $usID = mysql_insert_id();
	copy("./image/ico.jpg","./ico/$usID.jpg");
   $temp = new template;
   $_SESSION[logUser] = $this->getFullInfo($usID);
   unset($temp);
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);
 }

 public function destroy($user) {
  $lng = new language;
  try {
   if ($this->owner->level != $this->levelAdmin) throw new error($lng->accessDenied);
   $sql = "DELETE FROM $this->table WHERE id = $user";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $opinion = new opinion;
   $opinion->owner = $this->owner;
   $opinion->destroyFromItem("user",$user);
   unset($video);
   $comment = new comment;
   $comment->owner = $this->owner;
   $comment->destroyFromItem("user",$user);
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);  
 }

 public function getAllName() {
  $sql = "SELECT name FROM $this->table ORDER BY name";
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }

 public function getInfo($id) {
  $get = $this->getFullInfo($id);
  $get->password = "";
  return $get;
  
 }

 private function getFullInfo($id) {
  return $this->getFullInfoFromItem("id",$id);
 }
 
 private function getFullInfoFromItem($item,$value) {
  $recommend = new recommend;
  $opinion = new opinion;
  $sql = "
   SELECT
    $this->table.id,
    $this->table.name,
    $this->table.password,
    $this->table.email,
    $this->table.description,
    $this->table.level,
    $this->table.login,
    (((SELECT COUNT($recommend->table.id) FROM $recommend->table WHERE $recommend->table.recommend = $this->table.id) + 1)*(COUNT($opinion->table.id))) AS recommend
   FROM $this->table
   LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.user
   WHERE $this->table.$item = '$value'
   GROUP BY $this->table.id
  ";
  unset($recommend);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $record = mysql_fetch_object($res);
  return $record; 
 }


 public function install() {
  $sql = "CREATE TABLE $this->table (
   id INT(25) UNSIGNED NOT NULL auto_increment PRIMARY KEY,
   name VARCHAR(32) UNIQUE NOT NULL,
   asciiName VARCHAR(32) UNIQUE NOT NULL,
   password VARCHAR(128) NOT NULL,
   email VARCHAR(128),
   description TEXT,
   level ENUM('1','2','3','4') NOT NULL,
   login DATETIME default '0000-00-00 00:00:00'
  )";
  mysql_query($sql) or die(__FILE__." : ".__LINE__." : ".$sql);
 }

 public function listAllLogged($order,$page,$name = "") {
  $saerch = new search;
  $name = $this->cut($this->utf2ascii($name));
  unset($search);
  $condition = "";
  foreach($name AS $item) {
   if ($condition) $condition .= " AND ";
   $condition .= "$this->table.asciiName LIKE '%$item%'";  
  }
  switch($order) {
   default: $order = "ORDER BY name"; break;
   case "login": $order = "ORDER BY login DESC"; break;
   case "recommend": $order = "ORDER BY recommend DESC"; break;
   case "similirity": $order = "ORDER BY similirity DESC"; break;
  }
  $limit = $page*$this->limit;  
  $recommend = new recommend;
  $owner = $this->owner->id;
  $opinion = new opinion;
  $tagRef = new tagReference;
  $userSim = new userSim;
  $sql = "
   SELECT    $this->table.id,
    $this->table.name AS name,
    $this->table.email,
    $this->table.description,
    $this->table.login,		
    (((SELECT COUNT($recommend->table.id) FROM $recommend->table WHERE $recommend->table.recommend = $this->table.id) + 1)*(SELECT COUNT($opinion->table.id) FROM $opinion->table WHERE $opinion->table.user = $this->table.id)) AS recommend,
	 (SELECT similarity FROM $userSim->table WHERE owner = $owner AND user = $this->table.id) AS similirity,
    $this->limit AS pageLimit,
    (SELECT COUNT($this->table.id) FROM $this->table WHERE $condition) AS pageHelp
   FROM $this->table
   LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.user
   LEFT JOIN $tagRef->table ON $opinion->table.book = $tagRef->table.book
   WHERE
   $condition
   GROUP BY $this->table.id
   $order
   LIMIT $limit,$this->limit
  ";
  unset($recommend);
  unset($opinion);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }

 public function listAll($order,$page,$name = "") {
  $saerch = new search;
  $name = $this->cut($this->utf2ascii($name));
  unset($search);
  $condition = "";
  foreach($name AS $item) {
   if ($condition) $condition .= " AND ";
   $condition .= "$this->table.asciiName LIKE '%$item%'";  
  }
  switch($order) {
   default: $order = "ORDER BY name"; break;
   case "login": $order = "ORDER BY login DESC"; break;
   case "recommend": $order = "ORDER BY recommend DESC"; break;
   case "id": $order = "ORDER BY id"; break;
   case "level": $order = "ORDER BY level DESC"; break;
  }
  $limit = $page*$this->limit;  
  $recommend = new recommend;
  $opinion = new opinion;
  $sql = "
   SELECT
    $this->table.id,
    $this->table.name AS name,
    $this->table.email,
    $this->table.description,
    $this->table.level,
    $this->table.login,		
    (((SELECT COUNT($recommend->table.id) FROM $recommend->table WHERE $recommend->table.recommend = $this->table.id) + 1)*(COUNT($opinion->table.id))) AS recommend,
    $this->limit AS pageLimit,
    (SELECT COUNT($this->table.id) FROM $this->table WHERE $condition) AS pageHelp
   FROM $this->table
   LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.user
   WHERE
   $condition
   GROUP BY $this->table.id
   $order
   LIMIT $limit,$this->limit
  ";
  unset($recommend);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 }

 public function logIn($name,$password) {
  $lng = new language;
  $password = sha1($password);
  $user = $this->getFullInfoFromItem("asciiName",$this->simpleName($name));
  try {
   if (!$user->id) throw new error($lng->wrongName);
   if ($user->password != $password) throw new error($lng->wrongPassword);
   $sql = "UPDATE $this->table SET login = now() WHERE id = $user->id";
   mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
   $user = $this->getInfo($user->id);
   $_SESSION["logUser"] = $user;
  }
  catch (error $exception) {
   $exception->scream();
  }
  unset($lng);
 } 
 
 public function sendEmail($userID,$subject,$content,$from) {
  $char = array("\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e","\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l","\xc4\xba"=>"l","\xc5\x88"=>"n","\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r","\xc5\x95"=>"r","\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u","\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z","\xc3\x81"=>"A","\xc3\x84"=>"A","\xc4\x8c"=>"C","\xc4\x8e"=>"D","\xc3\x89"=>"E","\xc4\x9a"=>"E","\xc3\x8d"=>"I","\xc4\xbd"=>"L","\xc4\xb9"=>"L","\xc5\x87"=>"N","\xc3\x93"=>"O","\xc3\x96"=>"O","\xc5\x90"=>"O","\xc3\x94"=>"O","\xc5\x98"=>"R","\xc5\x94"=>"R","\xc5\xa0"=>"S","\xc5\xa4"=>"T","\xc3\x9a"=>"U","\xc5\xae"=>"U","\xc3\x9c"=>"U","\xc5\xb0"=>"U","\xc3\x9d"=>"Y","\xc5\xbd"=>"Z",", " => " ", "," => " ");
  $content = strtr($text,$char);
  
 }
 
 public function simpleName($name) {
  $change = array(" "=>"-");
  return strtr($this->utf2ascii($name),$change);
 }

 public function topList($limit = 20) {
  $recommend = new recommend;
  $opinion = new opinion;
  $sql = "
   SELECT
  	 $this->table.id AS usID
   FROM $this->table
   LEFT JOIN $recommend->table ON $this->table.id = $recommend->table.recommend
   LEFT JOIN $opinion->table ON $this->table.id= $opinion->table.user
   GROUP BY usID
   ORDER BY ((SELECT COUNT($recommend->table.id) FROM $recommend->table WHERE $recommend->table.recommend = usID) + 1)*((SELECT COUNT($opinion->table.id) FROM $opinion->table WHERE $opinion->table.user = usID)) DESC
   LIMIT 0,$limit
  ";
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  $help = "";
  while ($record = mysql_fetch_object($res)) {
   if ($help) $help .= ", ";
   $help .= $record->usID;  
  }
  $sql = "
   SELECT
    $this->table.id,
    $this->table.name,
    $this->table.password,
    $this->table.email,
    $this->table.description,
    $this->table.level,
    $this->table.login,
    (((SELECT COUNT($recommend->table.id) FROM $recommend->table WHERE $recommend->table.recommend = $this->table.id) + 1)*(COUNT($opinion->table.id))) AS recommend
   FROM $this->table
   LEFT JOIN $opinion->table ON $this->table.id = $opinion->table.user
   WHERE $this->table.id IN ($help)
   GROUP BY $this->table.id
   ORDER BY $this->table.name
  ";
  unset($recommend);
  $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
  return $res;
 } 
 
 public function uninstall() {
  $sql = "DROP TABLE $this->table";
  mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);
 }
}
?>
