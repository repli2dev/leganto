function writerNameFirstList() {
	return writerList = new Array(
<?php
function __autoload($class) {
 require_once "./../class/".$class.".php";
}

$temp = new template;
$reader = new reader;
$reader->MySQLconnect();
unset($reader);
$lng = new language;

$writer = new writer;
$res = $writer->getAll();
$help = FALSE;
$i = 0;
while($record = mysql_fetch_object($res)) {
	if ($help) { echo ","; } else { $help = TRUE; }
	$i++;
	$record->name = $writer->getNameFirst($record->name);
	echo "'$i=$record->name'";
}
?>
	)
}