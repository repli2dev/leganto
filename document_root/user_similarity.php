<?php
require_once(dirname(__FILE__) . "/constants.php");

define("LIMIT_OF_OPINIONS", 10);
define("LIMIT_OF_RELATIVE_VALUE", 0.1);
define("LIMIT_OF_ABSOLUTE_VALUE", 3);

echo "\nLIMIT OF OPINIONS: " . LIMIT_OF_OPINIONS;
echo "\nLIMIT OF ABSOLUTE VALUE: " . LIMIT_OF_ABSOLUTE_VALUE;
echo "\nLIMIT OF RELATIVE VALUE: " . LIMIT_OF_RELATIVE_VALUE;

echo "\n";

function key_compare_func($key1, $key2)
{
    if ($key1 == $key2)
        return 0;
    else if ($key1 > $key2)
        return 1;
    else
        return -1;
}

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

$last = dibi::query("SELECT MAX([updated]) AS [last] FROM [user_similarity]")->fetch();

echo "\nLAST UPDATED: ".(empty($last["last"]) ? "NEVER" : $last["last"]);

$users = dibi::query("SELECT [id_user] FROM [opinion] GROUP BY [id_user] HAVING COUNT(*) >= %i", LIMIT_OF_OPINIONS)->fetchPairs("id_user","id_user");

$source = dibi::dataSource("SELECT [id_user], [inserted] FROM [opinion] GROUP BY [id_user] HAVING COUNT(*) >= %i", LIMIT_OF_OPINIONS);
if (!empty($last["last"])) {
	$source->where("[inserted] > %t", $last["last"]);
}
$updatedUsers = $source->fetchPairs("id_user","id_user");

$books = dibi::query("SELECT [id_user], [id_book] FROM [opinion] WHERE [id_user] IN %l", $users)->fetchAssoc("id_user,id_book");

echo "\nNUMBER OF SELECTED USERS: ".count($users);
echo "\nNUMBER OF USERS TO UPDATE: ".count($updatedUsers);
echo "\n\n";

dibi::query("DELETE FROM [user_similarity] WHERE [id_user_from] IN %l", $updatedUsers, " OR [id_user_to] IN %l", $updatedUsers);

$together = 0;
$insertedUsers = 0;
$maximum = 0;
$minimum = 0;

foreach ($users AS $from) {
	$fromBooks = $books[$from];
	$inserted = 0;
	foreach ($users AS $to) {
		if ($from == $to) {
			continue;
		}
		if (!in_array($from, $updatedUsers) && !in_array($to, $updatedUsers)) {
			continue;
		}
		$toBooks = $books[$to];
		// TODO: Compute better similarity
		$intersect = array_intersect_ukey($fromBooks, $toBooks,'key_compare_func');
		if ($intersect < LIMIT_OF_ABSOLUTE_VALUE) {
			continue;
		}
		$value = count($intersect)/count($fromBooks);
		if ($value < LIMIT_OF_RELATIVE_VALUE) {
			continue;
		}
		dibi::insert("user_similarity", array("id_user_from" => $from, "id_user_to" => $to, "value" => $value))->execute();
		$inserted++;
	}
	if ($inserted > $maximum) {
		$maximum = $inserted;
	}
	if ($inserted < $minimum) {
		$minimum = $inserted;
	}
	if ($inserted > 0) {
		$insertedUsers++;
	}
	$together += $inserted;
}

echo "\nNUMBER OF INSERTED ROWS: ".$together;
echo "\nNUMBER OF INSERTED USERS: ".$insertedUsers;
echo "\nMAXIMUM OF SIMILAR USERS: ". $maximum;
echo "\nMINUMUM OF SIMILAR USERS: ". $minimum;

die("\nDONE\n");
?>