<?php
function key_compare_func($key1, $key2)
{
    if ($key1 == $key2)
        return 0;
    else if ($key1 > $key2)
        return 1;
    else
        return -1;
}


// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/../app');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/../libs');

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

$config = new Config();
$config->add("host", "databases.savana.cz:13307");
$config->add("database", "preader_devel");
$config->add("username", "preader_devel");
$config->add("password", "Hublu.Mer");

dibi::connect($config);

$books = dibi::query("SELECT * FROM [book] LIMIT 0, 100")->fetchPairs("id_book","id_book");
$tags = dibi::query("SELECT * FROM [view_book_tag]")->fetchAssoc("id_book,id_tag");

dibi::query("TRUNCATE TABLE [book_similarity]");

foreach ($books AS $from) {
	foreach($books AS $to) {
		$fromTags = $tags[$from];
		$toTags = empty($tags[$to]) ? array() : $tags[$to]; 
		if (empty($fromTags)) {
			$value = 0;
		}
		else {
			$value = count(array_intersect_ukey($fromTags, $toTags,'key_compare_func'))/count($fromTags);
		}
		dibi::insert("book_similarity", array("id_book_from" => $from, "id_book_to" => $to, "value" => $value))->execute();
	}
}

die("DONE\n");
?>