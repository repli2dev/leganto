<?php
require_once dirname(__FILE__) . "/header.php";

Environment::loadConfig(APP_DIR . '/config.ini');

Debug::enable(Debug::DEVELOPMENT);

Debug::timer();

dibi::connect(Environment::getConfig("database"));

$similarity = new DatabaseSimilarity("opinion", "user", "book_title", "rating", 5);
$similarity->checkout();

echo "\nELAPSED TIME: ".Debug::timer()."\n";

echo "\nDONE\n";
?>
