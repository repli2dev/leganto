<?php
require_once(dirname(__FILE__) . "/constants.php");

require_once LIBS_DIR . '/Nette/loader.php';

// Loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

Debug::timer();

Debug::enable(Debug::DEVELOPMENT);

Environment::loadConfig(APP_DIR . '/config.ini');

dibi::connect(Environment::getConfig("database"));

$similarity = new DatabaseSimilarity("opinion", "user", "book", "rating", 5);
$similarity->checkout();

echo "\nELAPSED TIME: ".Debug::timer()."\n";

echo "\nDONE\n";
?>
