<?php
require_once(dirname(__FILE__) . "/constants.php");

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

echo "\n Dropping table with similarity... ";
dibi::query("DROP TABLE book_similarity");
echo dibi::$elapsedTime;

echo "\n Copying similarity view... ";
dibi::query("CREATE TABLE book_similarity SELECT * FROM view_book_similarity WHERE 1");
echo dibi::$elapsedTime;
 
die("\nDONE\n");
?>