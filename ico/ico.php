<?php
session_start();

function __autoload($class) {
 require_once "./../class/".$class.".php";
}

$reader = new reader;
$reader->MySQLconnect();
unset($reader);

$user = new user;

if (($_GET[action] == "reset") and ($user->owner->level = $user->levelAdmin)) {
 $sql = "SELECT id FROM reader_user ORDER BY id";
 $res = mysql_query($sql) or die (__FILE__." : ".__LINE__." : ".$sql);

 while ($record = mysql_fetch_object($res)) {
  copy("./../image/ico.jpg",$record->id.".jpg");
 }
}
else {
 $user->changeIco($_FILES[ico]);
}

$div = "../user.php?user=".$user->owner->id;
Header("Location: $div");
?>