<?php
require_once dirname(__FILE__) . "/header.php";

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

$delimiter = "#$#";

// New opinion
//$result = dibi::query("SELECT * FROM view_opinion WHERE 1")->fetchAll();
//
//foreach($result as $row) {
//	$content = array();
//	$temp = array();
//	$temp[] = $row["id_book_title"];
//	$temp[] = $row["book_title"];
//	$temp[] = $row["content"];
//	$temp[] = $row["rating"];
//	$content["type"] = FeedItemEntity::TYPE_NEW_OPINION;
//	$content["id_user"] = $row["id_user"];
//	$content["content"] = implode($delimiter, $temp);
//	$content["inserted"] = $row["inserted"];
//	dibi::insert("feed_event",$content)->execute();
//}
// Change of opinion
//$result = dibi::query("SELECT * FROM view_opinion WHERE inserted != updated AND updated NOT LIKE '2010-11-20 17%'")->fetchAll();
//foreach($result as $row) {
//	$content = array();
//	$temp = array();
//	$temp[] = $row["id_book_title"];
//	$temp[] = $row["book_title"];
//	$temp[] = $row["content"];
//	$temp[] = null;
//	$temp[] = $row["rating"];
//	$temp[] = null;
//	$content["type"] = FeedItemEntity::TYPE_UPDATED_OPINION;
//	$content["id_user"] = $row["id_user"];
//	$content["content"] = implode($delimiter, $temp);
//	$content["inserted"] = $row["inserted"];
//	dibi::insert("feed_event",$content)->execute();
//}
// Shelving
$result = dibi::query("SELECT * FROM view_shelf_book WHERE 1")->fetchAll();
foreach($result as $row) {
	$content = array();
	$temp = array();
	$temp[] = $row["type"];
	$temp[] = $row["name"];
	$temp[] = $row["id_book_title"];
	$temp[] = $row["title"];
	$temp[] = "";
	$content["type"] = FeedItemEntity::TYPE_SHELVED;
	$content["id_user"] = $row["id_user"];
	$content["content"] = implode($delimiter, $temp);
	$content["inserted"] = $row["inserted"];
	dibi::insert("feed_event",$content)->execute();
}
echo "DONE";
// TODO: dokoncit i pro ostatni triggery