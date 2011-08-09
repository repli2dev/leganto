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
// DO NOT USE! BREAKS FEED DUE MISSING OLD RATING AND OLD CONTENT
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
//$result = dibi::query("SELECT * FROM view_shelf_book WHERE 1")->fetchAll();
//foreach($result as $row) {
//	$content = array();
//	$temp = array();
//	$temp[] = $row["type"];
//	$temp[] = $row["name"];
//	$temp[] = $row["id_book_title"];
//	$temp[] = $row["title"];
//	$temp[] = "";
//	$content["type"] = FeedItemEntity::TYPE_SHELVED;
//	$content["id_user"] = $row["id_user"];
//	$content["content"] = implode($delimiter, $temp);
//	$content["inserted"] = $row["inserted"];
//	dibi::insert("feed_event",$content)->execute();
//}
// New users
//$result = dibi::query("SELECT * FROM user WHERE inserted NOT LIKE '2010-11-20%'")->fetchAll();
//foreach($result as $row) {
//	$content = array();
//	$temp = array();
//	$content["type"] = FeedItemEntity::TYPE_NEW_USER;
//	$content["id_user"] = $row["id_user"];
//	$content["content"] = "";
//	$content["inserted"] = $row["inserted"];
//	dibi::insert("feed_event",$content)->execute();
//}
// New discussions
//$result = dibi::query("SELECT * FROM topic WHERE 1")->fetchAll();
//foreach($result as $row) {
//	$content = array();
//	$temp = array();
//	$temp[] = $row["id_topic"];
//	$temp[] = $row["name"];
//	$content["type"] = FeedItemEntity::TYPE_NEW_DISCUSSION;
//	$content["id_user"] = $row["id_user"];
//	$content["content"] = implode($delimiter, $temp);
//	$content["inserted"] = $row["inserted"];
//	dibi::insert("feed_event",$content)->execute();
//}
// New posts
$result = dibi::query("SELECT * FROM view_post WHERE 1")->fetchAll();
foreach($result as $row) {
	$content = array();
	$temp = array();
	$temp[] = $row["id_discussion"];
	$temp[] = $row["discussion_name"];
	$temp[] = $row["id_discussable"];
	$temp[] = $row["id_discussed"];
	$temp[] = "";
	$temp[] = $row["content"];
	$content["type"] = FeedItemEntity::TYPE_NEW_POST;
	$content["id_user"] = $row["id_user"];
	$content["content"] = implode($delimiter, $temp);
	$content["inserted"] = $row["inserted"];
	dibi::insert("feed_event",$content)->execute();
}
// New books
//$result = dibi::query("SELECT * FROM book_title WHERE 1")->fetchAll();
//foreach($result as $row) {
//	$content = array();
//	$temp = array();
//	$temp[] = $row["id_book_title"];
//	$temp[] = $row["title"];
//	if(isSet($row["subtitle"])) $temp[] = $row["subtitle"];
//	$content["type"] = FeedItemEntity::TYPE_NEW_BOOK;
//	$content["id_user"] = NULL;
//	$content["content"] = implode($delimiter, $temp);
//	$content["inserted"] = $row["inserted"];
//	dibi::insert("feed_event",$content)->execute();
//}
echo "DONE";
