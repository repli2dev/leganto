function userNameList() {
	return userList = new Array(
<?php
function __autoload($class) {
 require_once "./../class/".$class.".php";
}

$temp = new template;
$reader = new reader;
$reader->MySQLconnect();
unset($reader);
$lng = new language;

$user = new user;
$res = $user->getAllName();
unset($user);
$help = FALSE;
$i = 0;
while($user = mysql_fetch_object($res)) {
	if ($help) { echo ","; } else { $help = TRUE; }
	$i++;
	echo "'$i=$user->name'";
}
?>
	)
}