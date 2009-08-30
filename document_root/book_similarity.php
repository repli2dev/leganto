<?php
require_once(dirname(__FILE__) . "/constants.php");

define("LIMIT_OF_TAGS", 5);
define("LIMIT_OF_RELATIVE_VALUE", 0.25);
define("LIMIT_OF_ABSOLUTE_VALUE", 3);

echo "\nLIMIT OF TAGS: " . LIMIT_OF_TAGS;
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

$last = dibi::query("SELECT MAX([updated]) AS [last] FROM [book_similarity]")->fetch();

echo "\nLAST UPDATED: ".(empty($last["last"]) ? "NEVER" : $last["last"]);

$books = dibi::query("SELECT * FROM [tagged] GROUP BY [id_book] HAVING COUNT([id_tag]) >= %i", LIMIT_OF_TAGS)->fetchPairs("id_book","id_book");
$source = dibi::dataSource("SELECT * FROM [tagged] GROUP BY [id_book] HAVING COUNT([id_tag]) >= %i ORDER BY [updated] DESC", LIMIT_OF_TAGS);
if (!empty($last["last"])) {
	$source->where("[updated] > %t", $last["last"]);
}
$updatedBooks = $source->fetchPairs("id_book","id_book");
$tags = dibi::query("SELECT [id_book], [id_tag] FROM [tagged] WHERE [id_book] IN %l", $books)->fetchAssoc("id_book,id_tag");

echo "\nNUMBER OF SELECTED BOOKS: ".count($books);
echo "\nNUMBER OF BOOKS TO UPDATE: ".count($updatedBooks);
echo "\n\n";

dibi::query("DELETE FROM [book_similarity] WHERE [id_book_to] IN %l", $updatedBooks, " OR [id_book_from] IN %l", $updatedBooks);

$together = 0;
$insertedBooks = 0;
$maximum = 0;
$minimum = 0;

foreach ($books AS $from) {
	$fromTags = $tags[$from];
	$inserted = 0;
	foreach($books AS $to) {
		if ($from == $to) {
			continue;
		}
		if (!in_array($from, $updatedBooks) && !in_array($to, $updatedBooks)) {
			continue;
		}
		$toTags = $tags[$to];
		$intersect = array_intersect_ukey($fromTags, $toTags,'key_compare_func');
		if ($intersect < LIMIT_OF_ABSOLUTE_VALUE) {
			continue;
		}
		$value = count($intersect)/count($fromTags);
		if ($value < LIMIT_OF_RELATIVE_VALUE) {
			continue;
		}
		dibi::insert("book_similarity", array("id_book_from" => $from, "id_book_to" => $to, "value" => $value))->execute();
		$inserted++;
	}
	if ($inserted > $maximum) {
		$maximum = $inserted;
	}
	if ($inserted < $minimum) {
		$minimum = $inserted;
	}
	if ($inserted > 0) {
		$insertedBooks++;
	}
	$together += $inserted;
}

echo "\nNUMBER OF INSERTED ROWS: ".$together;
echo "\nNUMBER OF INSERTED BOOKS: ".$insertedBooks;
echo "\nMAXIMUM OF SIMILAR BOOKS: ". $maximum;
echo "\nMINUMUM OF SIMILAR BOOKS: ". $minimum;

die("\nDONE\n");
?>