function bookTitleList() {
	return bookList = new Array(
<?php
function __autoload($class) {
 require_once "./../class/".$class.".php";
}

$temp = new template;
$reader = new reader;
$reader->MySQLconnect();
unset($reader);
$lng = new language;

$book = new book;
$res = $book->getAll();
$help = FALSE;
$i = 0;
while($book = mysql_fetch_object($res)) {
	if ($help) { echo ","; } else { $help = TRUE; }
	$i++;
	echo "'$i=$book->title'";
}
?>
	)
}